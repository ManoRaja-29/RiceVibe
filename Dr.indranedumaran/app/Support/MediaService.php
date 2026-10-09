<?php

namespace App\Support;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Safe image pipeline: files are decoded and re-encoded as WebP with GD, which
 * strips any embedded payload/metadata and produces a full-size + thumbnail copy.
 */
class MediaService
{
    public const MAX_WIDTH = 1920;

    public const THUMB_WIDTH = 640;

    public function store(UploadedFile $file, ?string $alt = null): Media
    {
        $info = @getimagesize($file->getRealPath());
        if (! $info || ! in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            throw new \InvalidArgumentException('Unsupported or corrupt image.');
        }

        $img = match ($info[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($file->getRealPath()),
            IMAGETYPE_PNG => imagecreatefrompng($file->getRealPath()),
            IMAGETYPE_WEBP => imagecreatefromwebp($file->getRealPath()),
        };

        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($file->getRealPath());
            $angle = match ($exif['Orientation'] ?? 1) {
                3 => 180, 6 => -90, 8 => 90, default => 0,
            };
            if ($angle) {
                $img = imagerotate($img, $angle, 0);
            }
        }

        imagepalettetotruecolor($img);
        imagealphablending($img, false);
        imagesavealpha($img, true);

        $dir = 'media/'.date('Y/m');
        $name = Str::random(24);
        $main = $this->resize($img, self::MAX_WIDTH);
        $thumb = $this->resize($img, self::THUMB_WIDTH);

        Storage::disk('public')->makeDirectory($dir);
        $mainPath = "$dir/$name.webp";
        $thumbPath = "$dir/$name-thumb.webp";
        imagewebp($main, Storage::disk('public')->path($mainPath), 82);
        imagewebp($thumb, Storage::disk('public')->path($thumbPath), 78);

        return Media::create([
            'path' => $mainPath,
            'thumb_path' => $thumbPath,
            'original_name' => Str::limit(preg_replace('/[^\w.\- ]/u', '', $file->getClientOriginalName()), 150, ''),
            'mime' => 'image/webp',
            'size' => Storage::disk('public')->size($mainPath),
            'width' => imagesx($main),
            'height' => imagesy($main),
            'alt' => $alt,
        ]);
    }

    public function delete(Media $media): void
    {
        Storage::disk('public')->delete(array_filter([$media->path, $media->thumb_path]));
        $media->delete();
    }

    private function resize(\GdImage $img, int $maxWidth): \GdImage
    {
        $w = imagesx($img);
        if ($w <= $maxWidth) {
            return $img;
        }

        return imagescale($img, $maxWidth, -1, IMG_BICUBIC);
    }
}

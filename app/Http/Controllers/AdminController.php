<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'products' => Product::query()->with('category')->orderBy('sort_order')->get(),
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'banners' => Banner::query()->orderBy('sort_order')->get(),
            'contents' => SiteContent::query()->orderBy('section')->get(),
            'enquiries' => Enquiry::query()->latest()->limit(20)->get(),
            'pageTitle' => 'Ricevibe Admin | Dashboard',
        ]);
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['image_path'] = $request->file('image_file')?->store('products', 'public') ?? '/static/assets/product-rice.webp';
        $data['gallery'] = $this->storeGalleryImages($request);
        $data['sort_order'] = (Product::max('sort_order') ?? -1) + 1;
        unset($data['gallery_files'], $data['replace_gallery']);
        Product::create($data);

        return back()->with('status', 'Product added.');
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request, $product);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        if ($request->hasFile('image_file')) {
            $this->deleteStoredImage($product->image_path);
            $data['image_path'] = $request->file('image_file')->store('products', 'public');
        }

        if ($request->hasFile('gallery_files')) {
            $newGallery = $this->storeGalleryImages($request);
            if ($request->boolean('replace_gallery')) {
                foreach ($product->gallery ?? [] as $image) {
                    $this->deleteStoredImage($image);
                }
                $data['gallery'] = $newGallery;
            } else {
                $data['gallery'] = array_values(array_merge($product->gallery ?? [], $newGallery));
            }
        }

        unset($data['gallery_files'], $data['replace_gallery']);
        $product->update($data);

        return back()->with('status', 'Product updated.');
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $this->deleteStoredImage($product->image_path);
        foreach ($product->gallery ?? [] as $image) {
            $this->deleteStoredImage($image);
        }
        $product->delete();

        return back()->with('status', 'Product removed.');
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        $data = $this->validateBanner($request, true);
        $data['slug'] = Str::slug($data['title']);
        $data['image_path'] = $request->file('image_file')->store('banners', 'public');
        $data['sort_order'] = (Banner::max('sort_order') ?? -1) + 1;
        Banner::create($data);

        return back()->with('status', 'Banner added.');
    }

    public function updateBanner(Request $request, Banner $banner): RedirectResponse
    {
        $data = $this->validateBanner($request);
        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('image_file')) {
            $this->deleteStoredImage($banner->image_path);
            $data['image_path'] = $request->file('image_file')->store('banners', 'public');
        }

        $banner->update($data);

        return back()->with('status', 'Banner updated.');
    }

    public function destroyBanner(Banner $banner): RedirectResponse
    {
        $this->deleteStoredImage($banner->image_path);
        $banner->delete();

        return back()->with('status', 'Banner removed.');
    }

    public function updateContent(Request $request, string $section): RedirectResponse
    {
        abort_unless(SiteContent::query()->where('section', $section)->exists(), 404);
        $request->validate(['payload' => ['required', 'string', 'max:500000']]);

        try {
            $payload = json_decode($request->string('payload')->toString(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return back()->withErrors(['payload' => 'Content must be valid JSON.']);
        }

        SiteContent::query()->where('section', $section)->update(['payload' => $payload]);

        return back()->with('status', ucfirst($section).' content saved.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $slugRule = 'unique:products,slug'.($product ? ','.$product->id : '');

        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', $slugRule],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'pack' => ['nullable', 'string', 'max:180'],
            'price' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
            'usage' => ['nullable', 'string', 'max:5000'],
            'stock' => ['nullable', 'string', 'max:100'],
            'badge' => ['nullable', 'string', 'max:100'],
            'specs' => ['nullable', 'array'],
            'specs.*' => ['string', 'max:255'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string', 'max:500'],
            'image_file' => ['nullable', 'image', 'max:8192'],
            'gallery_files' => ['nullable', 'array', 'max:8'],
            'gallery_files.*' => ['image', 'max:8192'],
            'replace_gallery' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function validateBanner(Request $request, bool $imageRequired = false): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'image_file' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:8192'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function storeGalleryImages(Request $request): array
    {
        return collect($request->file('gallery_files', []))
            ->map(fn ($file) => $file->store('products/gallery', 'public'))
            ->values()
            ->all();
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path && !str_starts_with($path, '/')) {
            Storage::disk('public')->delete($path);
        }
    }
}

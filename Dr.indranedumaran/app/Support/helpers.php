<?php

use App\Models\Setting;
use Illuminate\Support\Str;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        $value = Setting::allCached()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }
}

if (! function_exists('media_url')) {
    function media_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}

if (! function_exists('video_embed_url')) {
    /** Convert a YouTube / Vimeo link into a privacy-friendly embed URL. */
    function video_embed_url(?string $url): ?string
    {
        if (! $url) {
            return null;
        }
        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([\w-]{11})~', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/'.$m[1];
        }
        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1].'?dnt=1';
        }

        return null;
    }
}

if (! function_exists('clean_html')) {
    /** Allow-list sanitiser for rich-text content authored in the admin editor. */
    function clean_html(?string $html): string
    {
        if (! $html) {
            return '';
        }
        $allowed = ['p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'a', 'ul', 'ol', 'li', 'h2', 'h3', 'h4', 'blockquote', 'img', 'figure', 'figcaption', 'iframe', 'hr', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'span'];
        $attrs = ['a' => ['href', 'title', 'target', 'rel'], 'img' => ['src', 'alt', 'width', 'height', 'loading'], 'iframe' => ['src', 'width', 'height', 'allowfullscreen', 'title'], 'span' => [], 'th' => ['colspan'], 'td' => ['colspan']];

        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8"?><div id="root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $walk = function (DOMNode $node) use (&$walk, $allowed, $attrs): void {
            foreach (iterator_to_array($node->childNodes) as $child) {
                if (! $child instanceof DOMElement) {
                    continue;
                }
                $tag = strtolower($child->tagName);
                if (! in_array($tag, $allowed, true)) {
                    // drop dangerous containers entirely, unwrap others
                    if (in_array($tag, ['script', 'style', 'object', 'embed', 'form', 'svg', 'math'], true)) {
                        $node->removeChild($child);

                        continue;
                    }
                    $walk($child);
                    while ($child->firstChild) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);

                    continue;
                }
                foreach (iterator_to_array($child->attributes) as $attr) {
                    $name = strtolower($attr->name);
                    $ok = in_array($name, $attrs[$tag] ?? [], true);
                    $val = trim($attr->value);
                    if ($ok && in_array($name, ['href', 'src'], true)) {
                        $safe = preg_match('~^(https?://|mailto:|tel:|/|#)~i', $val) === 1;
                        if ($tag === 'iframe') {
                            $safe = preg_match('~^https://(www\.youtube-nocookie\.com|www\.youtube\.com|player\.vimeo\.com)/~i', $val) === 1;
                        }
                        $ok = $safe;
                    }
                    if (! $ok) {
                        $child->removeAttribute($attr->name);
                    }
                }
                if ($tag === 'a') {
                    $child->setAttribute('rel', 'noopener noreferrer');
                }
                if ($tag === 'img') {
                    $child->setAttribute('loading', 'lazy');
                }
                if ($tag === 'iframe' && ! $child->hasAttribute('src')) {
                    $node->removeChild($child);

                    continue;
                }
                $walk($child);
            }
        };

        $root = $dom->getElementById('root');
        if (! $root) {
            return strip_tags($html);
        }
        $walk($root);
        $out = '';
        foreach ($root->childNodes as $c) {
            $out .= $dom->saveHTML($c);
        }

        return $out;
    }
}

if (! function_exists('reading_time')) {
    function reading_time(?string $html): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $html)) / 200));
    }
}

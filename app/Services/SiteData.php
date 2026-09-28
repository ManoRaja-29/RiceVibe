<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteContent;

class SiteData
{
    private ?array $data = null;

    public function all(): array
    {
        if ($this->data !== null) {
            return $this->data;
        }

        $data = SiteContent::query()->get()->mapWithKeys(
            fn (SiteContent $content) => [$content->section => $content->payload],
        )->all();

        $data['hero'] = Banner::query()->where('is_active', true)->orderBy('sort_order')->get();
        $data['categories'] = Category::query()->orderBy('sort_order')->get();
        $data['products'] = Product::query()->with('category')->where('is_active', true)->orderBy('sort_order')->get();

        return $this->data = $data;
    }
}

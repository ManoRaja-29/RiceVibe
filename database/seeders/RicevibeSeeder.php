<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RicevibeSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('seeders/site-data.json')), true, 512, JSON_THROW_ON_ERROR);

        foreach ($data['categories'] as $order => $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'image_path' => $category['image'] ?? null,
                    'description' => $category['description'] ?? null,
                    'sort_order' => $order,
                ],
            );
        }

        foreach ($data['products'] as $order => $product) {
            $category = Category::where('slug', $product['category_slug'])->first();
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'category_id' => $category?->id,
                    'name' => $product['name'],
                    'pack' => $product['pack'] ?? null,
                    'price' => $product['price'] ?? null,
                    'image_path' => $product['image'] ?? null,
                    'gallery' => $product['gallery'] ?? [],
                    'description' => $product['description'] ?? null,
                    'specs' => $product['specs'] ?? [],
                    'usage' => $product['usage'] ?? null,
                    'stock' => $product['stock'] ?? null,
                    'badge' => $product['badge'] ?? null,
                    'sort_order' => $order,
                ],
            );
        }

        foreach ($data['hero'] as $order => $banner) {
            Banner::updateOrCreate(
                ['slug' => Str::slug($banner['title'])],
                [
                    'title' => $banner['title'],
                    'subtitle' => $banner['subtitle'] ?? null,
                    'image_path' => $banner['image'],
                    'button_text' => $banner['button_text'] ?? null,
                    'button_link' => $banner['button_link'] ?? null,
                    'sort_order' => $order,
                ],
            );
        }

        foreach ($data as $section => $payload) {
            if (! in_array($section, ['categories', 'products', 'hero'], true)) {
                SiteContent::updateOrCreate(['section' => $section], ['payload' => $payload]);
            }
        }

        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if ($email && $password) {
            User::firstOrCreate(
                ['email' => $email],
                ['name' => 'RiceVibe Admin', 'password' => Hash::make($password)],
            );
        }
    }
}

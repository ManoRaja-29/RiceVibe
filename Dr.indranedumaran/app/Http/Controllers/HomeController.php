<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\PregnancyStage;
use App\Models\Service;
use App\Support\Seo;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __invoke()
    {
        $data = Cache::remember('home.data', 600, function () {
            $featured = Post::published()->with('category')->where('is_featured', true)->latest('published_at')->limit(3)->get();
            if ($featured->count() < 3) {
                $featured = $featured->concat(
                    Post::published()->with('category')->whereNotIn('id', $featured->pluck('id'))->latest('published_at')->limit(3 - $featured->count())->get()
                );
            }

            return [
                'featured' => $featured,
                'services' => Service::published()->get()->groupBy('section'),
                'stages' => PregnancyStage::published()->get(),
                'conferences' => Conference::published()->orderByDesc('start_date')->limit(3)->get(),
                'gallery' => GalleryItem::published()->limit(8)->get(),
            ];
        });

        return view('home', $data + ['seo' => Seo::make(null, null, null, 'website', [Seo::person()])]);
    }
}

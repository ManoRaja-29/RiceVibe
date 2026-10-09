<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $urls = [];
            foreach (['home' => '/', 'about' => '/about', 'blogs.index' => '/blogs', 'activities.index' => '/activities',             'contact' => '/contact', 'qa.index' => '/consult-qa', 'stories' => '/stories'] as $path) {
                $urls[] = [url($path), null];
            }
                        foreach (\App\Models\Question::published()->get(['id', 'updated_at']) as $q) {
                            $urls[] = [route('qa.show', $q->id), $q->updated_at];
                        }
            foreach (array_keys(Service::SECTIONS) as $s) {
                $urls[] = [route($s === 'maternity' ? 'maternity' : $s), null];
            }
            foreach (Service::published()->get() as $s) {
                $urls[] = [route($s->section.'.show', $s->slug), $s->updated_at];
            }
            foreach (Post::published()->get(['slug', 'updated_at']) as $p) {
                $urls[] = [route('blogs.show', $p->slug), $p->updated_at];
            }
            foreach (Conference::published()->where('is_placeholder', false)->get(['slug', 'updated_at']) as $c) {
                $urls[] = [route('activities.show', $c->slug), $c->updated_at];
            }

            return '<'.'?xml version="1.0" encoding="UTF-8"?>'."\n".view('sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}

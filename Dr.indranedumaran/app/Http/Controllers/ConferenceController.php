<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Support\Seo;

class ConferenceController extends Controller
{
    public function index()
    {
        $events = Conference::published()->orderByDesc('start_date')->get();

        return view('blog.activities', [
            'byYear' => $events->groupBy(fn ($e) => $e->start_date->format('Y')),
            'seo' => Seo::make('Conferences & professional activities', 'Timeline of conferences, workshops and professional events attended by '.setting('doctor_name').', with highlights and photos.'),
        ]);
    }

    public function show(string $slug)
    {
        $event = Conference::published()->where('slug', $slug)->firstOrFail();
        $schema = [[
            '@context' => 'https://schema.org', '@type' => 'Event', 'name' => $event->title,
            'startDate' => $event->start_date->toDateString(), 'endDate' => ($event->end_date ?? $event->start_date)->toDateString(),
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'location' => ['@type' => 'Place', 'name' => $event->venue ?: $event->city ?: 'TBC', 'address' => $event->city ?: 'TBC'],
            'description' => $event->summary,
        ]];

        return view('blog.activity', [
            'event' => $event,
            'embed' => video_embed_url($event->video_url),
            'others' => Conference::published()->whereKeyNot($event->id)->orderByDesc('start_date')->limit(3)->get(),
            'seo' => Seo::make($event->meta_title ?: $event->title, $event->meta_description ?: $event->summary, media_url($event->cover_image), 'article', $event->is_placeholder ? [] : $schema, route('activities.show', $event->slug), noindex: $event->is_placeholder),
        ]);
    }
}

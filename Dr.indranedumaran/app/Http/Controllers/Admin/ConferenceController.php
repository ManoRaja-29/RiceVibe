<?php

namespace App\Http\Controllers\Admin;

use App\Models\Conference;

class ConferenceController extends CrudController
{
    protected string $model = Conference::class;

    protected string $route = 'admin.conferences';

    protected string $title = 'Conferences & events';

    protected string $singular = 'conference / event';

    protected array $searchable = ['title', 'venue', 'city'];

    protected string $orderBy = 'start_date';

    protected function defaults(): array
    {
        return ['status' => 'draft', 'type' => 'conference'];
    }

    protected function filters(): array
    {
        return ['status' => ['label' => 'All statuses', 'options' => ['draft' => 'Draft', 'published' => 'Published']]];
    }

    protected function columns(): array
    {
        return [
            ['Title', fn ($c) => $c->title],
            ['Type', fn ($c) => $c->type_label],
            ['Date', fn ($c) => $c->date_range],
            ['Venue', fn ($c) => trim($c->venue.' '.$c->city) ?: '—'],
            ['Status', fn ($c) => $c->status, 'badge'],
        ];
    }

    protected function fields(): array
    {
        return array_merge([
            ['name' => 'title', 'label' => 'Title', 'rules' => 'required|max:220'],
            ['name' => 'slug', 'label' => 'URL slug', 'rules' => 'nullable|max:220'],
            ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => Conference::TYPES, 'rules' => 'required|in:'.implode(',', array_keys(Conference::TYPES))],
            ['name' => 'start_date', 'label' => 'Start date', 'type' => 'date', 'rules' => 'required|date'],
            ['name' => 'end_date', 'label' => 'End date', 'type' => 'date', 'rules' => 'nullable|date|after_or_equal:start_date'],
            ['name' => 'venue', 'label' => 'Venue', 'rules' => 'nullable|max:200'],
            ['name' => 'city', 'label' => 'City / country', 'rules' => 'nullable|max:120'],
            ['name' => 'role', 'label' => 'Role', 'rules' => 'nullable|max:160', 'help' => 'e.g. Delegate, Speaker, Panellist, Faculty.'],
            ['name' => 'summary', 'label' => 'Short summary', 'type' => 'textarea', 'rows' => 3, 'rules' => 'nullable|max:600'],
            ['name' => 'highlights', 'label' => 'Highlights', 'type' => 'textarea', 'rows' => 5, 'rules' => 'nullable|max:3000', 'help' => 'One highlight per line.'],
            ['name' => 'body', 'label' => 'Full write-up', 'type' => 'richtext', 'rules' => 'nullable'],
            ['name' => 'cover_image', 'label' => 'Cover image', 'type' => 'image'],
            ['name' => 'photos', 'label' => 'Photo gallery', 'type' => 'images'],
            ['name' => 'video_url', 'label' => 'Video link', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['draft' => 'Draft', 'published' => 'Published'], 'rules' => 'required|in:draft,published', 'section' => 'Publishing'],
            ['name' => 'is_placeholder', 'label' => 'This is placeholder content', 'type' => 'toggle', 'section' => 'Publishing', 'help' => 'Shows a "sample entry" badge publicly. Turn off once real details are entered.'],
        ], $this->seo());
    }
}

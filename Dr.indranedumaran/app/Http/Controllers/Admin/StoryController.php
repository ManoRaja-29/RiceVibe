<?php

namespace App\Http\Controllers\Admin;

use App\Models\Story;

class StoryController extends CrudController
{
    protected string $model = Story::class;

    protected string $route = 'admin.stories';

    protected string $title = 'Patient stories';

    protected string $singular = 'story';

    protected array $searchable = ['author_name', 'body', 'visit_for'];

    protected string $orderBy = 'sort_order';

    protected string $orderDir = 'asc';

    protected function defaults(): array
    {
        return ['is_published' => true];
    }

    protected function columns(): array
    {
        return [
            ['Patient', fn ($s) => $s->author_name],
            ['Visited for', fn ($s) => $s->visit_for ?: '—'],
            ['Story', fn ($s) => \Illuminate\Support\Str::limit($s->body, 70)],
            ['Status', fn ($s) => $s->is_published ? 'published' : 'draft', 'badge'],
        ];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'author_name', 'label' => 'Patient name (first name + initial)', 'rules' => 'required|max:120'],
            ['name' => 'visit_for', 'label' => 'Visited for', 'rules' => 'nullable|max:160'],
            ['name' => 'body', 'label' => 'Story', 'type' => 'textarea', 'rows' => 8, 'rules' => 'required|max:3000'],
            ['name' => 'source', 'label' => 'Source / note', 'rules' => 'nullable|max:160', 'help' => 'e.g. Verified patient review. Only publish with the patient’s permission.'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'toggle'],
        ];
    }
}

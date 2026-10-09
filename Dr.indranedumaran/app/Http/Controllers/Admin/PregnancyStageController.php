<?php

namespace App\Http\Controllers\Admin;

use App\Models\PregnancyStage;

class PregnancyStageController extends CrudController
{
    protected string $model = PregnancyStage::class;

    protected string $route = 'admin.pregnancy';

    protected string $title = 'Pregnancy journey';

    protected string $singular = 'pregnancy stage';

    protected string $orderBy = 'sort_order';

    protected string $orderDir = 'asc';

    protected function defaults(): array
    {
        return ['is_published' => true];
    }

    protected function columns(): array
    {
        return [
            ['Stage', fn ($s) => $s->title],
            ['Label', fn ($s) => $s->label ?? '—'],
            ['Order', fn ($s) => $s->sort_order],
            ['Status', fn ($s) => $s->is_published ? 'published' : 'draft', 'badge'],
        ];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Title', 'rules' => 'required|max:150'],
            ['name' => 'label', 'label' => 'Label', 'rules' => 'nullable|max:60', 'help' => 'e.g. "Weeks 1–12".'],
            ['name' => 'summary', 'label' => 'Summary', 'type' => 'textarea', 'rows' => 4, 'rules' => 'nullable|max:700'],
            ['name' => 'body', 'label' => 'Detailed content', 'type' => 'richtext', 'rules' => 'nullable'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'toggle'],
        ];
    }
}

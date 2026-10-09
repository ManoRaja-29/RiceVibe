<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;

class ServiceController extends CrudController
{
    protected string $model = Service::class;

    protected string $route = 'admin.services';

    protected string $title = 'Services';

    protected string $singular = 'service';

    protected string $orderBy = 'section';

    protected string $orderDir = 'asc';

    protected function defaults(): array
    {
        return ['is_published' => true, 'section' => 'gynaecology'];
    }

    protected function filters(): array
    {
        return ['section' => ['label' => 'All sections', 'options' => Service::SECTIONS]];
    }

    protected function columns(): array
    {
        return [
            ['Title', fn ($s) => $s->title],
            ['Section', fn ($s) => Service::SECTIONS[$s->section] ?? $s->section],
            ['Order', fn ($s) => $s->sort_order],
            ['Status', fn ($s) => $s->is_published ? 'published' : 'draft', 'badge'],
        ];
    }

    protected function fields(): array
    {
        return array_merge([
            ['name' => 'section', 'label' => 'Section', 'type' => 'select', 'options' => Service::SECTIONS, 'rules' => 'required|in:'.implode(',', array_keys(Service::SECTIONS))],
            ['name' => 'title', 'label' => 'Title', 'rules' => 'required|max:200'],
            ['name' => 'slug', 'label' => 'URL slug', 'rules' => 'nullable|max:200'],
            ['name' => 'icon', 'label' => 'Icon key', 'type' => 'select', 'options' => collect(['heart', 'search', 'flower', 'drop', 'calendar', 'shield', 'sparkle', 'baby', 'scope', 'sun', 'leaf', 'chat'])->mapWithKeys(fn ($i) => [$i => ucfirst($i)])->all(), 'rules' => 'nullable|max:30', 'blank' => 'Default'],
            ['name' => 'summary', 'label' => 'Summary', 'type' => 'textarea', 'rows' => 3, 'rules' => 'nullable|max:500'],
            ['name' => 'body', 'label' => 'Detailed content', 'type' => 'richtext', 'rules' => 'nullable'],
            ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'toggle'],
        ], $this->seo());
    }
}

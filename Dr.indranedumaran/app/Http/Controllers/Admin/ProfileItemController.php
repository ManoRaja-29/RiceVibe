<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProfileItem;

class ProfileItemController extends CrudController
{
    protected string $model = ProfileItem::class;

    protected string $route = 'admin.profile-items';

    protected string $title = 'Profile: qualifications & timeline';

    protected string $singular = 'profile entry';

    protected string $orderBy = 'type';

    protected string $orderDir = 'asc';

    protected function defaults(): array
    {
        return ['is_published' => true, 'type' => 'qualification'];
    }

    protected function filters(): array
    {
        return ['type' => ['label' => 'All types', 'options' => ProfileItem::TYPES]];
    }

    protected function columns(): array
    {
        return [
            ['Title', fn ($i) => $i->title],
            ['Type', fn ($i) => ProfileItem::TYPES[$i->type] ?? $i->type],
            ['Period', fn ($i) => $i->period ?? '—'],
            ['Placeholder', fn ($i) => $i->is_placeholder ? 'placeholder' : 'verified', 'badge'],
            ['Status', fn ($i) => $i->is_published ? 'published' : 'draft', 'badge'],
        ];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ProfileItem::TYPES, 'rules' => 'required|in:'.implode(',', array_keys(ProfileItem::TYPES))],
            ['name' => 'title', 'label' => 'Title', 'rules' => 'required|max:200'],
            ['name' => 'subtitle', 'label' => 'Institution / subtitle', 'rules' => 'nullable|max:200'],
            ['name' => 'period', 'label' => 'Year / period', 'rules' => 'nullable|max:60'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 4, 'rules' => 'nullable|max:1000'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
            ['name' => 'is_placeholder', 'label' => 'Placeholder – not yet verified', 'type' => 'toggle', 'help' => 'Untick once the real detail has been confirmed by the doctor.'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'toggle'],
        ];
    }
}

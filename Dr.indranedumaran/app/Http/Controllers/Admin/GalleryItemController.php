<?php

namespace App\Http\Controllers\Admin;

use App\Models\GalleryItem;

class GalleryItemController extends CrudController
{
    protected string $model = GalleryItem::class;

    protected string $route = 'admin.gallery';

    protected string $title = 'Gallery';

    protected string $singular = 'gallery item';

    protected array $searchable = ['caption'];

    protected string $orderBy = 'sort_order';

    protected string $orderDir = 'asc';

    protected function defaults(): array
    {
        return ['is_published' => true];
    }

    protected function columns(): array
    {
        return [
            ['Caption', fn ($g) => $g->caption],
            ['Category', fn ($g) => $g->category ?? '—'],
            ['Image', fn ($g) => $g->image ? 'uploaded' : 'none'],
            ['Order', fn ($g) => $g->sort_order],
            ['Status', fn ($g) => $g->is_published ? 'published' : 'draft', 'badge'],
        ];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'caption', 'label' => 'Caption', 'rules' => 'required|max:200'],
            ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
            ['name' => 'category', 'label' => 'Category', 'rules' => 'nullable|max:60'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'toggle'],
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;

class CategoryController extends CrudController
{
    protected string $model = Category::class;

    protected string $route = 'admin.categories';

    protected string $title = 'Blog categories';

    protected string $singular = 'category';

    protected array $searchable = ['name'];

    protected string $orderBy = 'sort_order';

    protected string $orderDir = 'asc';

    protected function columns(): array
    {
        return [
            ['Name', fn ($c) => $c->name],
            ['Slug', fn ($c) => $c->slug],
            ['Posts', fn ($c) => $c->posts()->count()],
            ['Order', fn ($c) => $c->sort_order],
        ];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'rules' => 'required|max:80'],
            ['name' => 'slug', 'label' => 'URL slug', 'rules' => 'nullable|max:100'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 3, 'rules' => 'nullable|max:400'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
        ];
    }
}

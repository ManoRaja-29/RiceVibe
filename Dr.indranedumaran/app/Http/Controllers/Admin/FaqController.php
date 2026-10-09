<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;

class FaqController extends CrudController
{
    protected string $model = Faq::class;

    protected string $route = 'admin.faqs';

    protected string $title = 'FAQs';

    protected string $singular = 'FAQ';

    protected array $searchable = ['question', 'answer'];

    protected string $orderBy = 'sort_order';

    protected string $orderDir = 'asc';

    protected function defaults(): array
    {
        return ['is_published' => true];
    }

    protected function columns(): array
    {
        return [
            ['Question', fn ($f) => $f->question],
            ['Group', fn ($f) => ucfirst($f->section)],
            ['Order', fn ($f) => $f->sort_order],
            ['Status', fn ($f) => $f->is_published ? 'published' : 'draft', 'badge'],
        ];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'question', 'label' => 'Question', 'rules' => 'required|max:255'],
            ['name' => 'answer', 'label' => 'Answer', 'type' => 'textarea', 'rows' => 6, 'rules' => 'required|max:3000'],
            ['name' => 'section', 'label' => 'Group', 'type' => 'select', 'options' => ['general' => 'General', 'fertility' => 'Fertility', 'gynaecology' => 'Gynaecology', 'maternity' => 'Pregnancy'], 'rules' => 'required|in:general,fertility,gynaecology,maternity'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'toggle'],
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class PostController extends CrudController
{
    protected string $model = Post::class;

    protected string $route = 'admin.posts';

    protected string $title = 'Blog posts';

    protected string $singular = 'blog post';

    protected array $searchable = ['title', 'excerpt'];

    protected function defaults(): array
    {
        return ['status' => 'draft', 'author_name' => setting('doctor_name', 'Dr. Indhra Nedumaran')];
    }

    protected function filters(): array
    {
        return [
            'status' => ['label' => 'All statuses', 'options' => ['draft' => 'Draft', 'published' => 'Published']],
            'category_id' => ['label' => 'All categories', 'options' => Category::orderBy('name')->pluck('name', 'id')->all()],
        ];
    }

    protected function scopeIndex($query): void
    {
        $query->with('category');
    }

    protected function columns(): array
    {
        return [
            ['Title', fn ($p) => $p->title],
            ['Category', fn ($p) => $p->category?->name ?? '—'],
            ['Status', fn ($p) => $p->status === 'published' && ! $p->is_live ? 'scheduled' : $p->status, 'badge'],
            ['Publish date', fn ($p) => $p->published_at?->format('d M Y H:i') ?? '—'],
            ['Views', fn ($p) => number_format($p->views)],
        ];
    }

    protected function fields(): array
    {
        return array_merge([
            ['name' => 'title', 'label' => 'Title', 'rules' => 'required|max:200'],
            ['name' => 'slug', 'label' => 'URL slug', 'rules' => 'nullable|max:200', 'help' => 'Auto-generated from the title if left blank.'],
            ['name' => 'category_id', 'label' => 'Category', 'type' => 'select', 'options' => Category::orderBy('name')->pluck('name', 'id')->all(), 'rules' => 'nullable|exists:categories,id', 'blank' => 'Uncategorised'],
            ['name' => 'excerpt', 'label' => 'Excerpt', 'type' => 'textarea', 'rows' => 3, 'rules' => 'nullable|max:500', 'help' => 'Shown on blog cards.'],
            ['name' => 'body', 'label' => 'Article', 'type' => 'richtext', 'rules' => 'required'],
            ['name' => 'featured_image', 'label' => 'Featured image', 'type' => 'image'],
            ['name' => 'image_alt', 'label' => 'Image alt text', 'rules' => 'nullable|max:200', 'help' => 'Describe the image for screen readers.'],
            ['name' => 'video_url', 'label' => 'Video link (YouTube / Vimeo)', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
            ['name' => 'author_name', 'label' => 'Author', 'rules' => 'required|max:120'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['draft' => 'Draft', 'published' => 'Published'], 'rules' => 'required|in:draft,published', 'section' => 'Publishing'],
            ['name' => 'published_at', 'label' => 'Publish date & time', 'type' => 'datetime', 'rules' => 'nullable|date', 'section' => 'Publishing', 'help' => 'Set a future date with status "Published" to schedule the post.'],
            ['name' => 'is_featured', 'label' => 'Feature on homepage', 'type' => 'toggle', 'section' => 'Publishing'],
        ], $this->seo());
    }

    protected function beforeSave(array &$data, ?Model $record): void
    {
        $data['reading_time'] = reading_time($data['body']);
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Support\Seo;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $term = trim((string) $request->query('q'));
        $category = $request->query('category') ? Category::where('slug', $request->query('category'))->first() : null;

        $query = Post::published()->with('category')->latest('published_at');
        if ($category) {
            $query->where('category_id', $category->id);
        }
        if ($term !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(fn ($w) => $w->where('title', 'like', $like)->orWhere('excerpt', 'like', $like)->orWhere('body', 'like', $like));
        }

        $posts = $query->paginate(9)->withQueryString();
        $hero = (! $term && ! $category && $posts->currentPage() === 1) ? $posts->first() : null;

        $canonical = route('blogs.index', array_filter(['category' => $category?->slug, 'page' => $posts->currentPage() > 1 ? $posts->currentPage() : null]));

        return view('blog.index', [
            'posts' => $posts, 'hero' => $hero, 'term' => $term, 'activeCategory' => $category,
            'categories' => Category::withCount(['posts' => fn ($q) => $q->published()])->orderBy('sort_order')->get(),
            'seo' => Seo::make($category ? $category->name.' articles' : 'Blogs & health education', 'Educational articles on gynaecology, fertility, pregnancy and women’s wellness by '.setting('doctor_name').'.', null, 'website', [], $canonical, noindex: $term !== ''),
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $post = Post::published()->with('category')->where('slug', $slug)->firstOrFail();

        $seen = $request->session()->get('seen_posts', []);
        if (! in_array($post->id, $seen)) {
            $post->increment('views');
            $request->session()->push('seen_posts', $post->id);
        }

        $related = Post::published()->with('category')->whereKeyNot($post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')->limit(3)->get();

        $schema = [[
            '@context' => 'https://schema.org', '@type' => 'BlogPosting',
            'headline' => $post->title, 'description' => $post->meta_description ?: $post->excerpt,
            'image' => $post->image_url ?: asset('images/logo-full.webp'),
            'datePublished' => $post->published_at?->toIso8601String(), 'dateModified' => $post->updated_at->toIso8601String(),
            'author' => ['@type' => 'Person', 'name' => $post->author_name],
            'publisher' => ['@type' => 'Organization', 'name' => setting('site_name'), 'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo-full.webp')]],
            'mainEntityOfPage' => route('blogs.show', $post->slug),
        ]];

        return view('blog.show', [
            'post' => $post, 'related' => $related,
            'embed' => video_embed_url($post->video_url),
            'seo' => Seo::make($post->meta_title ?: $post->title, $post->meta_description ?: $post->excerpt, $post->image_url, 'article', $schema, route('blogs.show', $post->slug), extra: ['published' => $post->published_at]),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'pageTitle' => 'Ricevibe | Eco-Friendly Rice Straws and Hospitality Essentials',
            'metaDescription' => 'Biodegradable rice straws, wooden stirrers, and garnish picks for hospitality and retail.',
        ]);
    }

    public function shop(Request $request): View
    {
        $products = Product::query()->with('category')->where('is_active', true);
        $category = $request->string('category')->toString();
        $search = $request->string('q')->trim()->toString();

        if ($category !== '') {
            $products->whereHas('category', fn ($query) => $query->where('slug', $category));
        }

        if ($search !== '') {
            $products->where(fn ($query) => $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        }

        match ($request->string('sort')->toString()) {
            'low-to-high' => $products->orderByRaw('price IS NULL')->orderBy('price'),
            'high-to-low' => $products->orderByRaw('price IS NULL')->orderByDesc('price'),
            'name' => $products->orderBy('name'),
            default => $products->orderBy('sort_order'),
        };

        return view('shop.index', [
            'products' => $products->paginate(12)->withQueryString(),
            'selectedCategory' => $category,
            'search' => $search,
            'sort' => $request->string('sort')->toString(),
            'pageTitle' => 'Shop Eco-Friendly Essentials | Ricevibe',
            'metaDescription' => 'Explore Ricevibe rice straws, wooden stirrers, and garnish picks.',
        ]);
    }

    public function product(string $slug): View
    {
        $product = Product::query()->with(['category', 'category.products' => fn ($query) => $query->where('is_active', true)])
            ->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('shop.product', [
            'product' => $product,
            'related' => $product->category?->products->where('id', '!=', $product->id)->take(3) ?? collect(),
            'pageTitle' => $product->name.' | Ricevibe',
            'metaDescription' => $product->description,
        ]);
    }

    public function page(string $page): View
    {
        $pages = [
            'about' => ['title' => 'About Ricevibe | Natural Sustainable Products', 'description' => 'Learn about Ricevibe and our sustainable hospitality products.'],
            'gallery' => ['title' => 'Ricevibe Gallery | Product Range', 'description' => 'Explore Ricevibe products and hospitality moments.'],
            'media' => ['title' => 'Ricevibe Media & Press', 'description' => 'Ricevibe press mentions and brand stories.'],
            'faq' => ['title' => 'Frequently Asked Questions | Ricevibe', 'description' => 'Answers about Ricevibe products and materials.'],
            'brochure' => ['title' => 'Ricevibe Brochure', 'description' => 'Read and download the Ricevibe brochure.'],
            'certificates' => ['title' => 'Certificates | Ricevibe', 'description' => 'Ricevibe certificates and compliance information.'],
            'privacy' => ['title' => 'Privacy Policy | Ricevibe', 'description' => 'Ricevibe privacy policy.'],
            'terms' => ['title' => 'Terms & Conditions | Ricevibe', 'description' => 'Ricevibe terms and conditions.'],
            'shipping' => ['title' => 'Shipping Policy | Ricevibe', 'description' => 'Ricevibe shipping information.'],
            'returns' => ['title' => 'Return & Refund Policy | Ricevibe', 'description' => 'Ricevibe return and refund policy.'],
        ];

        abort_unless(isset($pages[$page]), 404);

        return view(in_array($page, ['privacy', 'terms', 'shipping', 'returns'], true) ? 'pages.policy' : 'pages.'.$page, [
            'pageTitle' => $pages[$page]['title'],
            'metaDescription' => $pages[$page]['description'],
            'policyKey' => $page,
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'pageTitle' => 'Contact Ricevibe | Chennai, India',
            'metaDescription' => 'Contact Ricevibe for retail, wholesale, and hospitality enquiries.',
        ]);
    }

    public function submitEnquiry(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:190'],
            'company' => ['nullable', 'string', 'max:160'],
            'enquiry_type' => ['required', 'string', 'max:80'],
            'product' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        Enquiry::create($validated);

        return back()->with('status', 'Thank you. Your enquiry has been received.');
    }

    public function sitemap(): Response
    {
        $paths = [
            '/', '/shop', '/about-us', '/media', '/gallery', '/certificates', '/faq', '/contact',
            '/privacy-policy', '/terms-and-conditions', '/shipping-policy', '/returns-policy',
        ];

        foreach (Product::query()->where('is_active', true)->orderBy('sort_order')->get(['slug']) as $product) {
            $paths[] = '/shop/'.$product->slug;
        }

        $entries = collect($paths)->map(fn (string $path) => '<url><loc>'.e(url($path)).'</loc><changefreq>weekly</changefreq></url>')->implode('');

        return response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$entries.'</urlset>', 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}

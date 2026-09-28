<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteController extends Controller
{
    private const SITE_URL = 'https://ricevibe.in';

    public function home() { return view('home', $this->meta('Ricevibe | Eco-Friendly Rice Straws, Wooden Stirrer & Garnish Sticks', 'Ricevibe supplies eco-friendly rice straws, wooden stirrers, and garnish sticks for hospitality and retail in Chennai, India.', '/')); }

    public function shop(Request $request)
    {
        $category = strtolower(trim($request->string('category', 'all')));
        $search = strtolower(trim($request->string('q', '')));
        $sort = $request->string('sort', 'featured')->toString();

        $query = Product::query()->with('category')->where('active', true);
        if ($category !== 'all') $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        if ($search !== '') $query->where(fn ($q) => $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]));
        match ($sort) {
            'low-to-high' => $query->orderByRaw('price IS NULL, price ASC'),
            'high-to-low' => $query->orderByRaw('price IS NULL, price DESC'),
            'name' => $query->orderBy('name'),
            default => $query->orderBy('sort_order')->orderBy('id'),
        };

        return view('shop', array_merge($this->meta('Shop Eco-Friendly Dining Essentials | Ricevibe', 'Explore Ricevibe rice straws, wooden stirrers and garnish sticks for cafés, restaurants, retail and bulk hospitality needs.', '/shop'), [
            'products' => $query->get(), 'category' => $category, 'search' => $search, 'sort' => $sort,
        ]));
    }

    public function product(string $slug)
    {
        $product = Product::with('category')->where('slug', $slug)->where('active', true)->firstOrFail();
        $related = Product::where('category_id', $product->category_id)->whereKeyNot($product->id)->where('active', true)->limit(3)->get();
        return view('product', array_merge($this->meta("{$product->name} | Ricevibe", $product->description ?? '', "/shop/{$slug}"), compact('product', 'related')));
    }

    public function about() { return view('about', $this->meta('About Ricevibe | Natural, Sustainable, Food-Grade Products', 'Ricevibe brings natural, biodegradable beverage accessories to restaurants, cafés and eco-conscious retail customers in India.', '/about-us')); }
    public function media() { return view('media', $this->meta('Ricevibe Media & Press | Stories, Articles and Videos', 'Read press mentions, discover brand stories, and watch Ricevibe product media and hospitality applications.', '/media')); }
    public function gallery() { return view('gallery', $this->meta('Ricevibe Gallery | Product Shots, Hospitality Uses & Packaging', 'Browse Ricevibe gallery moments featuring products, hospitality setups, events, packaging and behind-the-scenes stories.', '/gallery')); }
    public function certificates() { return view('certificates', $this->meta('Certificates | Ricevibe by JP Enterprises', 'View official Ricevibe and JP Enterprises certificates and compliance information for food-grade natural products.', '/certificates')); }
    public function brochure() { return view('brochure', $this->meta('Ricevibe Brochure | Ricevibe Enterprises Private Limited', 'Read the Ricevibe Enterprises Private Limited company brochure and download the official brochure PDF.', '/brochure')); }
    public function faq() { return view('faq', $this->meta('Frequently Asked Questions | Ricevibe', 'Learn about Ricevibe rice straws, product materials, usage and hospitality FAQs for retail and B2B buyers.', '/faq')); }
    public function contact(Request $request) { return view('contact', array_merge($this->meta('Contact Ricevibe | Chennai, India', 'Contact Ricevibe for retail, wholesale, hotel and café enquiries, or speak with our team in Chennai.', '/contact'), ['selected_product' => trim($request->string('product'))])); }
    public function privacy() { return $this->policy('privacy', 'Privacy Policy | Ricevibe', 'Ricevibe privacy policy for website visits and business enquiries.', '/privacy-policy'); }
    public function terms() { return $this->policy('terms', 'Terms & Conditions | Ricevibe', 'Our terms and conditions for orders, product usage and enquiries.', '/terms-and-conditions'); }
    public function shipping() { return $this->policy('shipping', 'Shipping Policy | Ricevibe', 'Shipping information for Ricevibe natural product orders across India.', '/shipping-policy'); }
    public function returns() { return $this->policy('returns', 'Return & Refund Policy | Ricevibe', 'Return and refund policy for product concerns, damaged orders, and bulk purchases.', '/returns-policy'); }

    public function brochureDownload() { return response()->download(public_path('assets/ricevibe/jp_brochure.pdf'), 'Ricevibe-Brochure.pdf'); }
    public function brochureView() { return response()->file(public_path('assets/ricevibe/jp_brochure.pdf'), ['Content-Type' => 'application/pdf']); }
    public function robots() { return response("User-agent: *\nAllow: /\nSitemap: https://ricevibe.in/sitemap.xml\n", 200)->header('Content-Type', 'text/plain'); }

    public function sitemap()
    {
        $paths = ['', '/shop', '/about-us', '/media', '/gallery', '/certificates', '/faq', '/contact', '/privacy-policy', '/terms-and-conditions', '/shipping-policy', '/returns-policy'];
        foreach (Product::where('active', true)->pluck('slug') as $slug) $paths[] = "/shop/{$slug}";
        return response()->view('sitemap', ['paths' => $paths, 'siteUrl' => self::SITE_URL])->header('Content-Type', 'application/xml');
    }

    private function policy(string $page, string $title, string $description, string $path) { return view('policy', array_merge($this->meta($title, $description, $path), ['policy_page' => $page])); }
    private function meta(string $title, string $description, string $path): array { return ['page_title' => $title, 'meta_description' => $description, 'canonical_path' => $path]; }
}

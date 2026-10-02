<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\SiteContent;
use App\Models\User;
use Database\Seeders\RicevibeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RicevibeStorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_and_catalog_use_seeded_database_content(): void
    {
        $this->seed(RicevibeSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('homepage-banner-mixed-pack.png')
            ->assertSee('homepage-banner-straws.png')
            ->assertSeeInOrder(['gallery-preview', 'testimonial-section'], false)
            ->assertSee('class="whatsapp-float"', false)
            ->assertDontSee('>WA</a>', false);

        $this->get('/shop?category=rice-straws')
            ->assertOk()
            ->assertSee('Rice Straw 6.5mm X 20 cm');

        $this->get('/shop/rice-straw-8mm-20cm')
            ->assertOk()
            ->assertSee('Rice Straw 8mm X 20 cm');
    }

    public function test_public_content_routes_render(): void
    {
        $this->seed(RicevibeSeeder::class);

        foreach (['/about-us', '/gallery', '/media', '/faq', '/contact', '/brochure', '/certificates', '/privacy-policy'] as $path) {
            $this->get($path)->assertOk();
        }

        $this->get('/about-us')
            ->assertSee('Products That Help Your Business Grow')
            ->assertSee('Our directors')
            ->assertSee('Company credentials');

        $this->get('/brochure')
            ->assertSee('What we do')
            ->assertSee('Our story')
            ->assertSee('/static/assets/ricevibe/jp_brochure.pdf');

        $this->get('/')
            ->assertSee('class="social-icon"', false)
            ->assertDontSee('Instagram</a><a');
    }

    public function test_enquiries_are_saved_and_admin_dashboard_requires_login(): void
    {
        $this->seed(RicevibeSeeder::class);

        $this->post('/contact', [])->assertSessionHasErrors(['name', 'phone', 'email', 'enquiry_type', 'message']);
        $this->assertDatabaseCount('enquiries', 0);

        $this->post('/contact', [
            'name' => 'Test Buyer',
            'phone' => '9000000000',
            'email' => 'buyer@example.test',
            'company' => 'Test Cafe',
            'enquiry_type' => 'Bulk',
            'product' => 'Rice Straws',
            'message' => '<script>alert("x")</script>',
        ])->assertRedirect();

        $this->assertDatabaseHas('enquiries', ['email' => 'buyer@example.test']);
        $this->assertSame(1, Enquiry::count());
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk();

        $admin = User::create([
            'name' => 'RiceVibe Test Admin',
            'email' => 'admin@example.test',
            'password' => 'test-password',
        ]);

        $this->actingAs($admin)->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Store management')
            ->assertSee('Rice Straw 6.5mm X 20 cm')
            ->assertSee('admin-sidebar', false)
            ->assertSee('data-dialog-open="product-create-dialog"', false)
            ->assertSee('data-dialog-open="banner-create-dialog"', false)
            ->assertSee('Site content')
            ->assertSee('Recent enquiries')
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>', false);
    }

    public function test_admin_can_create_products_upload_banners_and_save_content(): void
    {
        $this->seed(RicevibeSeeder::class);

        $admin = User::create([
            'name' => 'RiceVibe Test Admin',
            'email' => 'admin@example.test',
            'password' => 'test-password',
        ]);
        $this->actingAs($admin);
        Storage::fake('public');

        $this->post('/admin/products', [
            'name' => 'Test Rice Straw',
            'slug' => '',
            'category_id' => Category::firstOrFail()->id,
            'pack' => '20 cm',
            'price' => '',
            'specs' => ['Natural rice and tapioca'],
        ])->assertRedirect();

        $this->assertDatabaseHas('products', ['slug' => 'test-rice-straw']);

        $this->post('/admin/banners', [
            'title' => 'Test Banner',
            'subtitle' => 'Seasonal range',
            'button_text' => 'Shop now',
            'button_link' => '/shop',
            'image_file' => UploadedFile::fake()->image('banner.png', 1200, 500),
        ])->assertRedirect();

        $banner = Banner::where('slug', 'test-banner')->firstOrFail();
        $this->assertTrue(Storage::disk('public')->exists($banner->image_path));

        $this->put('/admin/content/homepage', [
            'payload' => json_encode(['products_heading' => 'New collection'], JSON_THROW_ON_ERROR),
        ])->assertRedirect();

        $this->assertSame('New collection', SiteContent::where('section', 'homepage')->firstOrFail()->payload['products_heading']);
    }
}

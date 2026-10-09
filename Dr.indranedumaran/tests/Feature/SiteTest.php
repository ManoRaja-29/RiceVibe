<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_pages_render(): void
    {
        foreach (['/', '/about', '/contact', '/fertility', '/gynaecology', '/pregnancy-maternity-care', '/blogs', '/activities', '/sitemap.xml', '/fertility/pcos-care'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_qa_flow(): void
    {
        $this->get('/consult-qa')->assertOk();
        $this->get('/consult-qa/ask')->assertOk();

        $this->withSession([])->post('/consult-qa/ask', [
            'asker_name' => 'Asha K', 'asker_email' => 'asha@test.dev', 'title' => 'Is this normal?',
            'body' => 'A sufficiently long question body for the test.', 'consent' => 1, '_ft' => encrypt(time() - 10),
        ])->assertRedirect(route('qa.ask'));

        $q = \App\Models\Question::firstOrFail();
        $this->assertSame('pending', $q->status);
        $this->get('/consult-qa/'.$q->id)->assertNotFound();

        $admin = User::first();
        $this->actingAs($admin)->get('/admin/questions')->assertOk()->assertSee('Is this normal?');
        $this->actingAs($admin)->put('/admin/questions/'.$q->id, [
            'title' => $q->title, 'body' => $q->body, 'asker_name' => 'Asha K', 'asker_email' => 'asha@test.dev',
            'status' => 'published', 'answer' => 'General guidance reply.',
        ])->assertRedirect();

        $this->get('/consult-qa/'.$q->id)->assertOk()->assertSee('General guidance reply.')->assertDontSee('asha@test.dev');
        $this->get('/consult-qa')->assertOk()->assertSee('Is this normal?');
    }

    public function test_admin_requires_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_dashboard_and_lists(): void
    {
        $this->actingAs(User::first());
        foreach (['/admin', '/admin/posts', '/admin/conferences', '/admin/services', '/admin/faqs', '/admin/enquiries', '/admin/settings'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_contact_form_rejects_honeypot(): void
    {
        $this->post('/contact', ['name' => 'Bot', 'email' => 'b@example.com', 'type' => 'contact', 'message' => 'spam spam spam spam', 'consent' => 1, 'website' => 'http://spam'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseCount('enquiries', 0);
    }
}

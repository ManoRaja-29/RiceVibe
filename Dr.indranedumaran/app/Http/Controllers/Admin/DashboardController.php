<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Enquiry;
use App\Models\Faq;
use App\Models\Media;
use App\Models\Post;
use App\Models\Service;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'stats' => [
                ['Published posts', Post::published()->count(), route('admin.posts.index', ['status' => 'published'])],
                ['Drafts', Post::where('status', 'draft')->count(), route('admin.posts.index', ['status' => 'draft'])],
                ['Conferences & events', Conference::count(), route('admin.conferences.index')],
                ['Services', Service::count(), route('admin.services.index')],
                ['FAQs', Faq::count(), route('admin.faqs.index')],
            ['Questions awaiting reply', \App\Models\Question::where('status', 'pending')->count(), route('admin.questions.index', ['status' => 'pending'])],
                ['Media files', Media::count(), route('admin.media.index')],
                ['New enquiries', Enquiry::where('status', 'new')->count(), route('admin.enquiries.index', ['status' => 'new'])],
                ['Total post views', number_format((int) Post::sum('views')), route('admin.posts.index')],
            ],
            'enquiries' => Enquiry::latest()->limit(6)->get(),
            'topPosts' => Post::orderByDesc('views')->limit(5)->get(),
            'scheduled' => Post::where('status', 'published')->where('published_at', '>', now())->orderBy('published_at')->limit(5)->get(),
        ]);
    }
}

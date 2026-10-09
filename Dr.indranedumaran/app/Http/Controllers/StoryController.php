<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Support\Seo;

class StoryController extends Controller
{
    public function index()
    {
        return view('pages.stories', [
            'stories' => Story::published()->paginate(12),
            'seo' => Seo::make('Patient stories', 'Experiences shared by patients of '.setting('doctor_name').'.'),
        ]);
    }
}

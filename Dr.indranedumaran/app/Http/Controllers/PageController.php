<?php

namespace App\Http\Controllers;

use App\Models\ProfileItem;
use App\Support\Seo;

class PageController extends Controller
{
    public function about()
    {
        $items = ProfileItem::published()->get()->groupBy('type');

        return view('pages.about', [
            'items' => $items,
            'seo' => Seo::make('About '.setting('doctor_name'), 'Biography, qualifications, achievements and career timeline of '.setting('doctor_name').', '.setting('doctor_title').'.', null, 'profile', [Seo::person()]),
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'seo' => Seo::make('Contact', 'Contact '.setting('doctor_name').': clinic location, phone, WhatsApp and an online enquiry form.'),
            'formTime' => encrypt(time()),
        ]);
    }
}

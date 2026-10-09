<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\PregnancyStage;
use App\Models\Service;
use App\Support\Seo;

class ServiceController extends Controller
{
    private const PAGES = [
        'fertility' => ['title' => 'Fertility care', 'intro' => 'Gentle, structured support whether you are planning a pregnancy, have been trying for a while, or are living with a condition such as PCOS.', 'desc' => 'Fertility assessment, PCOS care and fertility treatment options explained by Dr. Indhra Nedumaran.'],
        'gynaecology' => ['title' => 'Gynaecology', 'intro' => 'Diagnosis and treatment of conditions affecting the female reproductive system — with clear explanations at every step.', 'desc' => 'Gynaecological disorders, procedures and menopause care with Dr. Indhra Nedumaran.'],
        'maternity' => ['title' => 'Pregnancy & maternity care', 'intro' => 'Support before, during and after pregnancy: antenatal check-ups, birth planning and postpartum recovery.', 'desc' => 'Prenatal care, birth planning and postpartum support with Dr. Indhra Nedumaran.'],
    ];

    public function index(string $section)
    {
        $page = self::PAGES[$section] ?? abort(404);

        return view('pages.services', [
            'section' => $section, 'page' => $page,
            'services' => Service::published()->where('section', $section)->get(),
            'faqs' => Faq::published()->where('section', $section)->get(),
            'stages' => $section === 'maternity' ? PregnancyStage::published()->get() : collect(),
            'seo' => Seo::make($page['title'], $page['desc']),
        ]);
    }

    public function show(string $slug, string $section)
    {
        abort_unless(isset(self::PAGES[$section]), 404);
        $service = Service::published()->where('section', $section)->where('slug', $slug)->firstOrFail();

        return view('pages.service', [
            'section' => $section, 'page' => self::PAGES[$section], 'service' => $service,
            'others' => Service::published()->where('section', $section)->whereKeyNot($service->id)->limit(4)->get(),
            'seo' => Seo::make($service->meta_title ?: $service->title, $service->meta_description ?: $service->summary, media_url($service->image), 'article'),
        ]);
    }
}

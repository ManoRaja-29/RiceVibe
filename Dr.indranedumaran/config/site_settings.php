<?php

/*
 | Admin-editable site settings. Each key is stored in the `settings` table and
 | read through the setting('key') helper. "default" values are seeded and are
 | verified details supplied by the clinic.
 */
return [
    'general' => [
        'label' => 'General & branding',
        'fields' => [
            ['key' => 'site_name',             'label' => 'Website name', 'default' => 'Dr. Indhra Nedumaran'],
                        ['key' => 'doctor_name', 'label' => 'Doctor name', 'default' => 'Dr. Indhra Nedumaran'],
            ['key' => 'doctor_title', 'label' => 'Professional title', 'default' => 'Consultant Obstetrician, Gynaecologist & Laparoscopic Surgeon'],
            ['key' => 'tagline', 'label' => 'Tagline', 'default' => 'Women · Maternity · Wellness · Beyond'],
            ['key' => 'doctor_photo', 'label' => 'Doctor portrait', 'type' => 'image', 'help' => 'Leave empty to use the bundled portrait.'],
            ['key' => 'doctor_photo_alt', 'label' => 'Portrait alt text', 'default' => 'Portrait of Dr. Indhra Nedumaran'],
            ['key' => 'footer_disclaimer', 'label' => 'Medical disclaimer (footer)', 'type' => 'textarea', 'default' => 'The information on this website is for general education and is not a substitute for personal medical advice, diagnosis or treatment. In an emergency, contact your nearest emergency service immediately.'],
        ],
    ],
    'contact' => [
        'label' => 'Contact & clinic',
        'fields' => [
            ['key' => 'clinic_name', 'label' => 'Clinic / hospital name', 'default' => 'A2Z Pregnancy Care'],
            ['key' => 'phone', 'label' => 'Phone', 'default' => '073393 38888'],
            ['key' => 'whatsapp', 'label' => 'WhatsApp number (digits only, with country code)', 'default' => '917339338888'],
            ['key' => 'email', 'label' => 'Public email', 'default' => '', 'help' => 'Optional. Hidden on the site while empty.'],
            ['key' => 'notify_email', 'label' => 'Send enquiry notifications to', 'default' => '', 'help' => 'Optional. Requires working mail settings in .env.'],
            ['key' => 'address', 'label' => 'Address', 'type' => 'textarea', 'default' => "P 39, Ground floor, Third Main Road, 6th Ave\nAnna Nagar, Chennai\nTamil Nadu 600040"],
            ['key' => 'hours', 'label' => 'Clinic hours (one line each)', 'type' => 'textarea', 'default' => '', 'help' => 'Optional. Hidden on the site while empty.'],
            ['key' => 'map_embed_url', 'label' => 'Google Maps embed URL', 'type' => 'url', 'help' => 'In Google Maps: Share → Embed a map → copy the src URL only.', 'default' => 'https://www.google.com/maps?q=P+39+Third+Main+Road+6th+Ave+Anna+Nagar+Chennai+600040&output=embed'],
            ['key' => 'map_link', 'label' => 'Google Maps directions link', 'type' => 'url', 'default' => 'https://www.google.com/maps/search/?api=1&query=P+39+Third+Main+Road+6th+Ave+Anna+Nagar+Chennai+600040'],
        ],
    ],
    'social' => [
        'label' => 'Social links',
        'fields' => [
            ['key' => 'facebook', 'label' => 'Facebook URL', 'type' => 'url'],
            ['key' => 'instagram', 'label' => 'Instagram URL', 'type' => 'url'],
            ['key' => 'youtube', 'label' => 'YouTube URL', 'type' => 'url'],
            ['key' => 'linkedin', 'label' => 'LinkedIn URL', 'type' => 'url'],
            ['key' => 'x_twitter', 'label' => 'X (Twitter) URL', 'type' => 'url'],
        ],
    ],
    'about' => [
        'label' => 'About page',
        'fields' => [
            ['key' => 'about_headline', 'label' => 'Headline', 'default' => 'Meet Dr. Indhra Nedumaran'],
            ['key' => 'about_bio', 'label' => 'Biography (separate paragraphs with a blank line)', 'type' => 'textarea', 'default' => "Dr. Indhra Nedumaran is a senior consultant obstetrician, gynaecologist, laparoscopic surgeon and infertility specialist based in Chennai, with over 28 years of clinical experience.\n\nShe is Senior Consultant Obstetrician & Gynaecologist at VS Hospitals, Kilpauk, and Founder & Director of A2Z Pregnancy Care, Anna Nagar, Chennai. Her areas of practice include high-risk pregnancy care, painless normal deliveries, C-section management, laparoscopic surgery, hysterectomy, adolescent menstrual disorders, and fertility care including IUI and IVF support.\n\nShe believes good care starts with listening, continues with clear explanations, and is completed by decisions made together with the patient."],
            ['key' => 'about_philosophy', 'label' => 'Care philosophy', 'type' => 'textarea', 'default' => 'Evidence-based, respectful and unhurried care — with time to ask every question.'],
            ['key' => 'about_photo', 'label' => 'About page photo', 'type' => 'image', 'help' => 'Leave empty to use the main portrait.'],
            ['key' => 'stat_experience', 'label' => 'Highlight 1 – value',             'default' => '28+'],
                        ['key' => 'stat_experience_label', 'label' => 'Highlight 1 – label', 'default' => 'Years of clinical experience'],
                        ['key' => 'stat_2', 'label' => 'Highlight 2 – value', 'default' => 'FOGSI'],
                        ['key' => 'stat_2_label', 'label' => 'Highlight 2 – label', 'default' => 'Society member'],
                        ['key' => 'stat_3', 'label' => 'Highlight 3 – value', 'default' => 'TNMC'],
                        ['key' => 'stat_3_label', 'label' => 'Highlight 3 – label', 'default' => 'Reg. No. 60859'],
        ],
    ],
    'home' => [
        'label' => 'Homepage content',
        'fields' => [
            ['key' => 'hero_eyebrow', 'label' => 'Hero – small heading', 'default' => 'Gynaecology · Fertility · Maternity'],
            ['key' => 'hero_title', 'label' => 'Hero – headline', 'default' => 'Thoughtful, personal care for every stage of a woman’s life'],
            ['key' => 'hero_text', 'label' => 'Hero – introduction', 'type' => 'textarea', 'default' => 'Dr. Indhra Nedumaran is a consultant obstetrician, gynaecologist and laparoscopic surgeon in Chennai, with over 28 years of experience in pregnancy care, gynaecological surgery and fertility support.'],
            ['key' => 'hero_cta_primary_label', 'label' => 'Hero – primary button text', 'default' => 'Pregnancy calculator'],
            ['key' => 'hero_cta_primary_url', 'label' => 'Hero – primary button link', 'default' => '/#pregnancy-calculator'],
            ['key' => 'hero_cta_secondary_label', 'label' => 'Hero – secondary button text', 'default' => 'Read the blog'],
            ['key' => 'hero_cta_secondary_url', 'label' => 'Hero – secondary button link', 'default' => '/blogs'],
            ['key' => 'about_preview_title', 'label' => 'About preview – title', 'default' => 'A doctor who explains, listens and stays by your side'],
            ['key' => 'about_preview_text', 'label' => 'About preview – text', 'type' => 'textarea', 'default' => 'Dr. Indhra combines senior clinical training with a calm, educational approach — from high-risk pregnancy care and painless normal deliveries to laparoscopic surgery and fertility support.'],
            ['key' => 'expertise_title', 'label' => 'Expertise – title', 'default' => 'Areas of care'],
            ['key' => 'expertise_text', 'label' => 'Expertise – intro', 'type' => 'textarea', 'default' => 'From first periods to menopause, and from planning a pregnancy to life after birth.'],
            ['key' => 'pregnancy_title', 'label' => 'Pregnancy journey – title', 'default' => 'Your pregnancy journey, step by step'],
            ['key' => 'pregnancy_text', 'label' => 'Pregnancy journey – intro', 'type' => 'textarea', 'default' => 'General information on what many women can expect. Every pregnancy is different — your own care plan will be personal to you.'],
            ['key' => 'robotic_title', 'label' => 'Robotic surgery spotlight – title', 'default' => 'Understanding robotic-assisted gynaecological surgery'],
            ['key' => 'robotic_text', 'label' => 'Robotic surgery spotlight – text', 'type' => 'textarea', 'default' => 'Educational overview: robotic-assisted surgery is a form of minimally invasive (keyhole) surgery in which the surgeon controls fine instruments from a console. Whether it is suitable depends on your condition, anatomy and the facilities available, and is always decided together with your surgeon.'],
            ['key' => 'robotic_points', 'label' => 'Robotic surgery – talking points (one per line)', 'type' => 'textarea', 'default' => "Performed through a few small incisions\nMagnified 3D view for the surgeon\nSuitability depends on the individual condition\nRisks and alternatives are always discussed beforehand"],
            ['key' => 'robotic_image', 'label' => 'Robotic surgery – image', 'type' => 'image'],
            ['key' => 'conferences_title', 'label' => 'Conferences – title', 'default' => 'Learning, sharing, staying current'],
            ['key' => 'conferences_text', 'label' => 'Conferences – intro', 'type' => 'textarea', 'default' => 'Dr. Indhra regularly takes part in medical conferences and workshops so that patients benefit from up-to-date, evidence-based practice.'],
            ['key' => 'gallery_title', 'label' => 'Gallery – title', 'default' => 'Moments & milestones'],
            ['key' => 'contact_title', 'label' => 'Contact section – title', 'default' => 'Let’s talk about your health'],
            ['key' => 'contact_text', 'label' => 'Contact section – text', 'type' => 'textarea', 'default' => 'Send an enquiry and our team will get back to you. For urgent symptoms, please contact emergency services or visit your nearest hospital.'],
        ],
    ],
    'seo' => [
        'label' => 'SEO & analytics',
        'fields' => [
            ['key' => 'default_meta_description', 'label' => 'Default meta description', 'type' => 'textarea', 'default' => 'Dr. Indhra Nedumaran – gynaecologist and women’s health specialist. Fertility, gynaecology, pregnancy and maternity care, plus educational articles for women.'],
            ['key' => 'og_image', 'label' => 'Default social share image (1200×630)', 'type' => 'image'],
            ['key' => 'google_site_verification', 'label' => 'Google Search Console verification code', 'default' => ''],
            ['key' => 'ga_measurement_id', 'label' => 'Google Analytics measurement ID (G-XXXX)', 'default' => ''],
        ],
    ],
];

<?php

namespace App\Support;

class Seo
{
    public static function make(?string $title = null, ?string $description = null, ?string $image = null, string $type = 'website', array $schema = [], ?string $canonical = null, bool $noindex = false, array $extra = []): array
    {
        $site = setting('site_name', config('app.name'));

        return [
            'title' => $title ? $title.' | '.$site : $site.' – '.setting('doctor_title'),
            'description' => $description ?: setting('default_meta_description'),
            'image' => $image ?: (media_url(setting('og_image')) ?: asset('images/logo-full.webp')),
            'type' => $type,
            'canonical' => $canonical ?: url()->current(),
            'noindex' => $noindex,
            'schema' => $schema,
            'extra' => $extra,
        ];
    }

    public static function person(): array
    {
        $same = array_values(array_filter(array_map(fn ($k) => setting($k), ['facebook', 'instagram', 'youtube', 'linkedin', 'x_twitter'])));

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => ['Physician', 'MedicalBusiness'],
            'name' => setting('doctor_name'),
            'description' => setting('default_meta_description'),
            'url' => url('/'),
            'image' => media_url(setting('doctor_photo')) ?: asset('images/doctor.webp'),
            'logo' => asset('images/logo-full.webp'),
            'medicalSpecialty' => ['Gynecologic', 'Obstetric'],
            'telephone' => setting('phone'),
            'email' => setting('email'),
            'address' => setting('address') ? ['@type' => 'PostalAddress', 'streetAddress' => str_replace("\n", ', ', setting('address'))] : null,
            'sameAs' => $same ?: null,
        ]);
    }
}

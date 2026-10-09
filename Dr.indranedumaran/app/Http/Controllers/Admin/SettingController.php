<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function edit(string $group = 'general')
    {
        $groups = config('site_settings');
        abort_unless(isset($groups[$group]), 404);

        return view('admin.settings', ['groups' => $groups, 'group' => $group, 'def' => $groups[$group]]);
    }

    public function update(Request $request, string $group, MediaService $media)
    {
        $def = config("site_settings.$group");
        abort_unless($def, 404);

        $rules = [];
        foreach ($def['fields'] as $f) {
            $type = $f['type'] ?? 'text';
            $rules[$f['key']] = match ($type) {
                'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'url' => ['nullable', 'url', 'max:600'],
                'textarea' => ['nullable', 'string', 'max:5000'],
                'toggle' => ['nullable'],
                default => ['nullable', 'string', 'max:500'],
            };
        }
        $data = Validator::make($request->all(), $rules)->validate();

        foreach ($def['fields'] as $f) {
            $key = $f['key'];
            $type = $f['type'] ?? 'text';
            if ($type === 'image') {
                if ($request->boolean('remove_'.$key)) {
                    Setting::put($key, null);
                }
                if ($request->hasFile($key)) {
                    Setting::put($key, $media->store($request->file($key))->path);
                }

                continue;
            }
            $value = $type === 'toggle' ? ($request->boolean($key) ? '1' : '0') : ($data[$key] ?? null);
            if ($key === 'whatsapp') {
                $value = preg_replace('/\D+/', '', (string) $value);
            }
            if ($key === 'map_embed_url' && $value && ! str_starts_with($value, 'https://www.google.com/maps/embed')) {
                return back()->withInput()->withErrors(['map_embed_url' => 'Use the src URL from Google Maps “Embed a map” (starts with https://www.google.com/maps/embed).']);
            }
            Setting::put($key, $value);
        }

        Cache::forget('home.data');

        return back()->with('status', $def['label'].' saved.');
    }
}

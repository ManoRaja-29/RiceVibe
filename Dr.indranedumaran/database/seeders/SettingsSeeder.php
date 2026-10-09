<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /** Seeds defaults from config/site_settings.php without overwriting values already saved. */
    public function run(): void
    {
        foreach (config('site_settings') as $group) {
            foreach ($group['fields'] as $f) {
                if (! array_key_exists('default', $f)) {
                    continue;
                }
                Setting::firstOrCreate(['key' => $f['key']], ['value' => $f['default']]);
            }
        }
    }
}

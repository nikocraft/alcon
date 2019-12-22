<?php

namespace App\Services;

use App\Models\Core\Settings\Setting;
use App\Models\Core\Design\ThemeSetting;

class WebsiteService
{
    public function updateSettings($path, $settings)
    {
        list($section, $settingPath) = $this->extractSection($path);

        $setting = Setting::where('key', $section)->first();

        foreach ($settings as $key => $value) {
            $keysPath = $settingPath ? $settingPath . '.' . $key : $key;
            $setting->meta->set($keysPath, $value);
            $setting->save();
        }
    }

    public function updateSetting($path, $value)
    {
        list($section, $settingPath) = $this->extractSection($path);
        $setting = Setting::where('key', $section)->first();
        $setting->meta->set($settingPath, $value);
        $setting->save();
    }

    public function getSettings() {
        $websiteSettings = Setting::all();
        $websiteSettings = collect($websiteSettings->toArray());

        $websiteSettings = $websiteSettings->mapWithKeys(function ($item) {
            $meta = collect($item['meta']);
            return [$item['key'] => $meta];
        });

        return $websiteSettings;
    }

    private function extractSection($path) {
        $section = null;
        $settingPath = null;

        if (strpos($path, '.') !== false) {
            $exp = explode('.', $path, 2);
            $section = $exp[0];
            $settingPath = $exp[1];
        }

        return array($section, $settingPath);
    }
}
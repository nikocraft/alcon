<?php

namespace App\Services;

use App\Models\Core\Settings\Website;
use App\Models\Core\Design\ThemeSetting;

class WebsiteService
{
    public function updateSettings($section, $settings)
    {
        foreach ($settings as $key => $value) {
            $this->updateSetting($section, $key, $value);
        }

        $settings = get_theme_setting($section);
        return $settings;
    }

    public function updateSetting($section, $key, $value)
    {
        $section = Website::where('key', $section)->first();
        $setting = Website::where('key', $key)->where('parent_id', $section->id)->update(['value' => $value]);

        return $setting;
    }

    public function getSettings() {
        $websiteSettings = Website::with('settings')->where('type', 'section')->get();
        $websiteSettings = collect($websiteSettings->toArray());

        $websiteSettings = $websiteSettings->mapWithKeys(function ($item) {
            $settings = collect($item['settings']);
            $settings = $settings->mapWithKeys(function ($setting) {
                $value = null;
                switch ($setting['type']) {
                    case 'string':
                        $value = $setting['value'];
                    break;
                    case 'integer':
                        $value = (int)$setting['value'];
                    break;
                    case 'boolean':
                        $value = filter_var($setting['value'], FILTER_VALIDATE_BOOLEAN);
                    break;
                }
                return [$setting['key']=> $value];
            });
            return [$item['key'] => $settings];
        });

        return $websiteSettings;
    }
}
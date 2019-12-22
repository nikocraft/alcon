<?php

namespace App\Services;

use App\Models\Core\Design\ThemeSetting;
use App\Services\SettingsService;
use App\Services\ThemeService;

class GlobalSettingsService
{
    protected $websiteService;
    protected $themeService;
    protected $settings;

    CONST WEBSITE_SETTINGS = "website";
    CONST THEME_SETTINGS = "theme";
    CONST THEME_FOLDER = "themeFolder";

    public function __construct()
	{
        $this->websiteService = new SettingsService();
        $this->themeService = new ThemeService();

        // Initialise settings with null value first
        $this->settings[self::WEBSITE_SETTINGS] = null;
        $this->settings[self::THEME_SETTINGS] = null;
        $this->settings[self::THEME_FOLDER] = config('laraone.default_theme.name');

        $websiteSettings = $this->websiteService->getSettings();
        $activeThemeId = data_get($websiteSettings, 'website.general.activeTheme');
        $cmsInstalled = data_get($websiteSettings, 'cms.installed');

        // If website installation has been completed, init the global settings otherwise do nothing.
        if($cmsInstalled) {
            $theme = $this->themeService->getTheme($activeThemeId);
            $themeSettings = $this->themeService->getSettings($activeThemeId);

            $this->settings = [];
            $this->settings[self::WEBSITE_SETTINGS] = $websiteSettings;
            $this->settings[self::THEME_SETTINGS] = $themeSettings;
            $this->settings[self::THEME_FOLDER] = optional($theme)->name;
        }
    }

    public function all() {
        return $this->json($this->settings);
    }

    public function get($settings) {
        return $this->json($this->settings[$settings]);
    }

    private function json($settings) {
        return json_decode(json_encode($settings));
    }
}
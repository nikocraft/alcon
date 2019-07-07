<?php

namespace App\Services\Themes;

use App\Models\Core\Settings\Website;
use App\Models\Core\Design\Theme;
use App\Models\Core\Design\ThemeSetting;
use App\Services\Zip\ZipArchive;
use App\Services\WebsiteService;
use File;

class ThemeService
{
    protected $theme;
    protected $activeTheme;
    protected $websiteService;

    public function __construct()
	{
        $this->websiteService = new WebsiteService();
        $websiteSettings = $this->websiteService->getSettings();
        $activeThemeId = data_get($websiteSettings, 'website.activeTheme');
        $this->activeTheme = Theme::with('sections.settings')->find($activeThemeId);
    }

    public function installTheme($themePath, $saveToDB = true)
    {
        $zip = new ZipArchive;
        $themeData = '';
        $themeJsonPath = "views/theme.json";

        $return = array();
        $return['message'] = '';
        $return['code'] = 422;
        $return['id'] = null;
        $return = json_decode(json_encode($return));

        if ($zip->open($themePath) === TRUE) {
            if($themeData = json_decode($zip->getFromName($themeJsonPath), false)) {

                $themeFolderName = str_replace(' ', '-', strtolower($themeData->name));

                // delete themes folder, if left over from previous failed install
                File::deleteDirectory(resource_path("themes" . DIRECTORY_SEPARATOR . $themeFolderName));
                File::deleteDirectory(public_path("themes" . DIRECTORY_SEPARATOR . $themeFolderName));

                if(!File::isDirectory(resource_path("themes" .DIRECTORY_SEPARATOR. $themeFolderName))) {
                    $errors1 = $zip->extractSubdirTo("views", resource_path('themes'. DIRECTORY_SEPARATOR . $themeFolderName));
                    $errors2 = $zip->extractSubdirTo("assets", public_path('themes' . DIRECTORY_SEPARATOR . $themeFolderName));

                // if there are no errors save the theme in database
                    if(count($errors1) == 0 && count($errors2) == 0) {
                        if($saveToDB) {
                            $theme = new Theme();
                            $theme->name = $themeData->name;
                            $theme->folder = $themeData->folder;
                            $theme->namespace = $themeData->namespace;
                            $theme->author = $themeData->author;
                            $theme->org = $themeData->org;
                            $theme->version = $themeData->version;
                            $theme->description = $themeData->description;
                            $theme->url = $themeData->url;
                            $theme->screenshots = $themeData->screenshots;
                            $theme->save();

                            $this->theme = $theme;

                            // theme settings are nested, we need import them correctly
                            $this->importThemeSettings($themeData->settings);
                            $this->websiteService->updateSetting('website', 'installed', 1);
                        }
                        $return->message = "Theme " . $themeData->name . " installed successfully!";
                        $return->code = 200;
                        if(isset($theme)) {
                            $return->id = $theme->id;
                        }
                    } else {
                        $return->message = "It seems that the themes folder is locked by another program. Close the editor and try again.";
                    }
                } else {
                    $return->message = "Unpacking of theme failed. It's possible that folder is locked.";
                }
            } else {
                $return->message = "Failed to extract theme data.";
            }
        } else {
            $return->message = "Theme zip failed to open.";
        }

        return $return;
    }

    public function getTheme($themeID)
    {
        $theme = Theme::find($themeID);

        return $theme;
    }

    public function getSettings($themeID)
    {
        $theme = Theme::with('sections.settings')->find($themeID);
        $themeSettings = optional($theme)->sections;
        if($themeSettings) {
            $themeSettings = $theme->buildSettingsRecursive($themeSettings);
        }

        return $themeSettings;
    }

    public function getActiveTheme()
    {
        return $this->activeTheme;
    }

    public function getActiveThemeSection($section)
    {
        return $this->getSection($this->activeTheme->id, $section);
    }

    public function getSection($themeId, $section)
    {
        $section = ThemeSetting::where('key', $section)->where('type', 'section')->where('theme_id', $themeId)->first();
        $settings = ThemeSetting::where('parent_id', $section->id)->where('theme_id', $themeId)->get();

        $settings = $this->castSettings($settings);
        return $settings;
    }

    protected function castSettings($settings)
    {
        $settings = $settings->map( function ($setting) {
            switch ($setting['type']) {
                case 'integer':
                    $setting['value'] = (int)$setting['value'];
                break;
                case 'boolean':
                    $setting['value'] = filter_var($setting['value'], FILTER_VALIDATE_BOOLEAN);
                break;
            }
            return $setting;
        });

        return $settings;
    }

    protected function buildSettingsRecursive($settings) {
        $output = collect($settings)->mapWithKeys(function ($setting) {
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
                default:
                    $value = $setting['value'];
            }
            if ($setting['type'] == 'section') {
                return [
                    $setting['key'] => $this->buildSettingsRecursive(isset($setting['settings']) ? $setting['settings'] : [])
                ];
            } else {
                return [$setting['key'] => $value];
            }
        });

        return $output;
    }

    private function importThemeSettings($settings = [], $parent = null)
    {
        foreach ($settings as $data) {
            $params = [
                'key' => $data->key,
                'label' => $data->label,
                'type' => $data->type,
                'value' => $data->default ?? null,
                'meta' => $data->meta ?? []
            ];
            $setting = $this->theme->createSetting($parent ? $parent->id : null, $params);
            if( isset($data->settings) ) {
                $this->importThemeSettings($data->settings, $setting);
            }
        }
    }
}

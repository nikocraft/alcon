<?php

namespace App\Services;

use App\Models\Core\Design\Theme;
use App\Models\Core\Design\ThemeSetting;
use App\Services\ZipArchiveService;
use App\Services\SettingsService;
use File;

class ThemeService
{
    protected $theme;
    protected $activeTheme;
    protected $websiteService;

    public function __construct()
	{
        $this->websiteService = new SettingsService();
        $websiteSettings = $this->websiteService->getSettings();
        $activeThemeId = data_get($websiteSettings, 'website.general.activeTheme');
        $this->activeTheme = Theme::with('sections.settings')->find($activeThemeId);
    }

    public function installTheme($themePath)
    {
        $zip = new ZipArchiveService;
        $themeData = '';
        $themeJsonPath = "views/theme.json";

        $result = array();
        $result['message'] = '';
        $result['code'] = 422;
        $result['id'] = null;
        $result = json_decode(json_encode($result));

        if ($zip->open($themePath) === TRUE) {
            if($themeData = json_decode($zip->getFromName($themeJsonPath), false)) {
                $themeFolderName = str_replace(' ', '-', strtolower($themeData->folder));

                // delete themes folder, if left over from previous failed install
                File::deleteDirectory(resource_path("themes" . DIRECTORY_SEPARATOR . $themeFolderName));
                File::deleteDirectory(public_path("themes" . DIRECTORY_SEPARATOR . $themeFolderName));
                // File::deleteDirectory(database_path("themes" . DIRECTORY_SEPARATOR . $themeFolderName));

                if(!File::isDirectory(resource_path("themes" .DIRECTORY_SEPARATOR. $themeFolderName))) {
                    $errors1 = $zip->extractSubdirTo("views", resource_path('themes'. DIRECTORY_SEPARATOR . $themeFolderName));
                    $errors2 = $zip->extractSubdirTo("assets", public_path('themes' . DIRECTORY_SEPARATOR . $themeFolderName));
                    // $errors3 = $zip->extractSubdirTo("seeds", database_path('themes' . DIRECTORY_SEPARATOR . $themeFolderName));

                    if(count($errors1) == 0 && count($errors2) == 0) {
                        $theme = new Theme();
                        $theme->name = $themeData->name;
                        $theme->folder = $themeData->folder;
                        $theme->namespace = $themeData->namespace;
                        $theme->author = $themeData->author;
                        $theme->org = $themeData->org;
                        $theme->version = $themeData->version;
                        $theme->description = $themeData->description;
                        $theme->url = $themeData->url;
                        $theme->releases_url = $themeData->releases_url;
                        $theme->download_url = $themeData->download_url;
                        $theme->save();

                        $this->theme = $theme;
                        $this->importThemeSettings($themeData->settings);
                        $result->message = "Theme " . $themeData->name . " installed successfully!";
                        $result->id = $theme->id;
                        $result->code = 200;

                        // run seeds
                        // $this->runSeeds($theme->version);
                    } else {
                        $result->message = "It seems that the themes folder is locked, not possible to update.";
                    }
                } else {
                    $result->message = "Unpacking of theme failed. It's seems theme already exists.";
                }
            } else {
                $result->message = "Failed to extract theme data.";
            }
        } else {
            $result->message = "Theme zip failed to open.";
        }

        return $result;
    }

    protected function getSeedFileName($version)
    {
        $seedFileName = 'Seed' . preg_replace("/[^a-zA-Z0-9]/", "", $version);
        return $seedFileName;
    }


    private function getReleaseIndex($releasesData, $version)
    {
        $index = 0;
        foreach($releasesData as $key => $value) {
            if ($value['version'] == $version) {
                $index = $value['index'];
                break;
            }
        }
        return $index;
    }

    /**
     * Sort index array in asc order
     * 
     * @param $first
     * @param $second
     * 
     * @return mixed
     */
    protected function ascSort($first, $second)
    {
      return ($first["index"] <= $second["index"]) ? -1 : 1;
    }

    private function runSeeds($version)
    {
        $json = json_decode(file_get_contents("https://github.com/nikocraft/alcon-theme/raw/master/releases.json"), true);
        $releaseList = $json['releasesData'];
        usort($releaseList, [$this, 'ascSort']);
        // $currentReleaseIndex = $this->getReleaseIndex($releaseList, $version);
        // // echo "currentReleaseIndex" . $currentReleaseIndex;
        // $updatesData = array_slice($releaseList, $currentReleaseIndex);

        foreach($releaseList as $key => $value) {
            $seedFile = $this->getSeedFileName($value['version']);
            if(file_exists(base_path('database/themes/alcon/' . $seedFile . '.php'))) {
                $class = 'Database\Themes\Alcon\\' . $seedFile;
                $seeder = new $class();
                $seeder->run();
            }
        }
    }

    public function updateTheme($themePath)
    {
        $zip = new ZipArchiveService;
        $themeData = '';
        $themeJsonPath = "views/theme.json";

        $result = array();
        $result['message'] = '';
        $result['code'] = 422;
        $result['id'] = null;
        $result = json_decode(json_encode($result));

        if ($zip->open($themePath) === TRUE) {
            if($themeData = json_decode($zip->getFromName($themeJsonPath), false)) {
                $themeFolderName = str_replace(' ', '-', strtolower($themeData->folder));
                if(File::isDirectory(resource_path("themes" .DIRECTORY_SEPARATOR. $themeFolderName))) {
                    $errors1 = $zip->extractSubdirTo("views", resource_path('themes'. DIRECTORY_SEPARATOR . $themeFolderName));
                    $errors2 = $zip->extractSubdirTo("assets", public_path('themes' . DIRECTORY_SEPARATOR . $themeFolderName));

                    if(count($errors1) == 0 && count($errors2) == 0) {
                        $theme = Theme::where('namespace', $themeData->namespace)->first();
                        $theme->version = $themeData->version;
                        $theme->description = $themeData->description;
                        $theme->url = $themeData->url;
                        $theme->releases_url = $themeData->releases_url;
                        $theme->download_url = $themeData->download_url;
                        $theme->save();

                        $this->theme = $theme;
                        $this->importThemeSettings($themeData->settings);
                        $result->message = ucfirst($themeData->name) . " theme updated successfully!";

                        // run theme seeds
                        // $this->

                        $result->code = 200;
                    } else {
                        $result->message = "It seems that the themes folder is locked, not possible to update.";
                    }
                } else {
                    $result->message = "Update of theme failed. Theme folder does not exist.";
                }
            } else {
                $result->message = "Failed to extract theme data.";
            }
        } else {
            $result->message = "Theme zip failed to open.";
        }

        return $result;
    }

    public function getTheme($themeID)
    {
        $theme = Theme::find($themeID);

        return $theme;
    }

    public function getThemeByFolderName($folder)
    {
        $theme = Theme::where('folder', $folder)->first();

        return $theme;
    }

    public function getThemeByNamespace($namespace)
    {
        $theme = Theme::where('namespace', $namespace)->first();

        return $theme;
    }

    public function getSettings($themeID)
    {
        $theme = Theme::with('sections.settings')->find($themeID);
        $themeSettings = optional($theme)->sections;
        $themeSettings = $themeSettings ? $this->buildSettingsRecursive($themeSettings) : null;

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
                case 'json':
                    $value = json_decode($setting['value'], true);
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
                'meta' => $data->meta ?? null
            ];

            $setting = $this->theme->updateOrCreateSetting($parent ? $parent->id : null, $params);
            if( isset($data->settings) ) {
                $this->importThemeSettings($data->settings, $setting);
            }
        }
    }
}

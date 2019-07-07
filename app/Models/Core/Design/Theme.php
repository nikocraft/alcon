<?php

namespace App\Models\Core\Design;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Settings\Website;
use App\Http\Resources\ThemeResource;

class Theme extends Model
{
    protected $table = "themes";

    protected $fillable = ['namespace', 'name', 'folder', 'author', 'org', 'version', 'description', 'screenshots', 'parent', 'url'];

    public function settings()
    {
        return $this->hasMany(ThemeSetting::class, 'theme_id', 'id');
    }

    public function sections()
    {
        return $this->hasMany(ThemeSetting::class, 'theme_id', 'id')->where([
            ['type', '=', 'section'],
            ['parent_id', '=', NULL],
        ]);
    }

    public function updateSetting($id, $value)
    {
        if(is_array($value) || is_object($value)) {
            $type = 'json';
            $value = json_encode($value);
        }

        $setting = $this->settings()->updateOrCreate(
            [ 'theme_id' => $this->id, 'id' => $id ],
            [ 'value' => $value ]
        );

        return $setting;
    }

    public function createSetting($parent, $params)
    {
        extract($params);
        if(is_array($value) || is_object($value)) {
            $type = 'json';
            $value = json_encode($value);
        }

        $dataToInsert = [
            'theme_id' => $this->id,
            'parent_id' => $parent,
            'key' => $key,
            'label' => $label,
            'value' => $value,
            'type' => $type
        ];

        if ($meta) {
            $dataToInsert['meta'] = $meta;
        }

        $setting = $this->settings()->create($dataToInsert);

        return $setting;
    }

    static public function getSection($theme, $section)
    {
        $section = ThemeSetting::where('key', $section)->where('type', 'section')->where('theme_id', $theme)->first();
        $settings = ThemeSetting::where('parent_id', $section->id)->where('theme_id', $theme)->get();

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

    static public function getSettingsData($activeTheme) {
        $theme = Theme::with('sections.settings')->find($activeTheme);
        $themeSettings = $theme->sections;
        $themeSettings = $theme->buildSettingsRecursive($themeSettings);
        return $themeSettings;
    }

    public function buildSettingsRecursive($settings) {
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

    static public function getSetting($theme, $section, $key)
    {
        $setting = ThemeSetting::where('key', $key)->where('theme_id', $theme)->select('theme_id', 'key', 'value', 'type')->first();

        return $setting ? $setting->value : '';
    }
}
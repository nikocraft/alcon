<?php

namespace App\Models\Core\Design;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $table = "themes";

    protected $fillable = ['namespace', 'name', 'folder', 'author', 'org', 'version', 'description', 'parent', 'url'];

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

    public function updateOrCreateSetting($parent, $params)
    {
        extract($params);
        if(is_array($value) || is_object($value)) {
            $type = 'json';
            $value = json_encode($value);
        }

        $dataToInsert = [
            'label' => $label,
            'type' => $type,
            'meta' => $meta
        ];

        $exists = $this->settings()->where('theme_id', $this->id)->where('parent_id', $parent)->where('key', $key)->exists();
        if (!$exists) {
            $dataToInsert['value'] = $value;
        }

        $setting = $this->settings()->updateOrCreate(
            [ 'theme_id' => $this->id, 'parent_id' => $parent, 'key' => $key ],
            $dataToInsert
        );

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

    static public function getSetting($theme, $section, $key)
    {
        $setting = ThemeSetting::where('key', $key)->where('theme_id', $theme)->select('theme_id', 'key', 'value', 'type')->first();

        return $setting ? $setting->value : '';
    }
}
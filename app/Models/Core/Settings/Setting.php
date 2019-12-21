<?php

namespace App\Models\Core\Settings;

use Illuminate\Database\Eloquent\Model;
// use Jenssegers\Agent\Agent;

class Setting extends Model
{
    protected $table = "settings";

    protected $fillable = ['parent_id', 'key', 'value', 'type', 'meta'];

    public function settings()
    {
        return $this->hasMany(Setting::class, 'parent_id', 'id')->orderBy('id', 'asc');
    }

    static public function getSettingsDataWithMeta() {
        $websiteSettings = Setting::with('settings')->where('type', 'section')->get();
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
                $tmp['value'] = $value;
                $tmp['meta'] = $setting['meta'];
                return [$setting['key']=> $value];
            });
            return [$item['key'] => $settings];
        });

        return $websiteSettings;
    }

    static public function createSetting($parent, $key, $value, $type = 'string', $meta)
    {
        $dataToInsert = [
            'parent_id' => $parent,
            'key' => $key,
            'value' => $value,
            'type' => $type,
            'meta' => $meta
        ];

        $setting = self::create($dataToInsert);

        return $setting;
    }
}

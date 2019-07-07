<?php
namespace App\Models\Core\Settings;

use Auth;

trait HasSettings {

    public function settings()
    {
        return $this->morphToMany(Setting::class, 'settingable');
    }

    public function getSettings() {
        $settings = array();
        foreach ($this->settings as $key => $setting) {
            $settings[$setting['key']] = $setting['value'];
        }
        return (object)$settings;
    }

    public function getSetting($section = null, $key)
    {
        return $this->settings()->where('section', $section)->where('key', $key)->first();
    }

    public function getSection($section)
    {
        return $this->settings()->where('section', $section)->get();
    }

    public function getSettingsFilteredForVue($prefix = 'init')
    {
        $settings = '{';
        foreach ($this->settings as $key => $setting) {
            if($setting['type'] == 'boolean') {
                $value = filter_var($setting['value'], FILTER_VALIDATE_BOOLEAN);
                $value = ($value) ? 'true' : 'false';
                $settings .= $prefix . ucfirst($setting['key']) . ': ' . $value . ', ';
            } else if($setting['type'] == 'string') {
                $settings .= $prefix . ucfirst($setting['key']) . ': ' . '\'' . $setting['value'] . '\'' . ', ';
            } else if($setting->type == 'integer') {
                $settings .= $prefix . ucfirst($setting->key) . ': ' . $setting['value'] . ', ';
            }
        }
        $settings .= '}';
        return $settings;
    }

    public function getSettingsFilteredForFrontendRendering()
    {
        $settings = '{';
        foreach ($this->settings as $key => $setting) {
            if($setting['type'] == 'boolean') {
                $value = filter_var($setting['value'], FILTER_VALIDATE_BOOLEAN);
                $value = ($value) ? 'true' : 'false';
                $settings .= $setting['key'] . ': ' . $value . ', ';
            } else if($setting['type'] == 'string') {
                $settings .= $setting['key'] . ': ' . '\'' . $setting['value'] . '\'' . ', ';
            } else if($setting->type == 'integer') {
                $settings .= $setting->key . ': ' . $setting['value'] . ', ';
            }
        }
        $settings .= '}';
        return $settings;
    }

    public function getPreparedSettings()
    {
        $output = $this->settings()->get(['id','key','type','value'])
            ->keyBy('key')
            ->map(function ($item, $key) {
                $value = null;

                if($item->type == 'boolean') {
                    $value = filter_var($item->value, FILTER_VALIDATE_BOOLEAN);
                    $value = (boolean) ($value);
                } else if($item->type == 'string') {
                    $value = (string) $item->value;
                } else if(in_array($item->type, ['integer', 'number'])) {
                    $value = (integer) $item->value;
                }
                return $value;
            });
        return $output;
    }

    public function exists($key)
    {
        return $this->settings()->where('key', $key)->exists();
    }

    public function setSetting($key, $value, $type = 'string', $section = null)
    {
        $setting = $this->settings()->updateOrCreate(
            ['key' => $key],
            ['key' => $key, 'value' => $value, 'type' => $type, 'section' => $section]
        );

        return true;
    }

    public function removeSetting($section, $key)
    {
        $toDelete = $this->getSetting($section, $key);
        if($toDelete) {
            $toDelete->delete();
            $this->settings()->detach($toDelete->id);
        }
    }
}

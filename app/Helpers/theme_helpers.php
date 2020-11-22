<?php
if (!function_exists('module_url')) {
    function module_url($filename = null)
    {
    	$module_path = app()->make('view.finder')->currentModule['slug'];
        return theme_url("/modules/$module_path/$filename");
    }
}

if (!function_exists('has_excerpt')) {
    function has_excerpt($content)
    {
        $blocks = $content->blocks;
        $excerptBlock = $blocks->firstWhere('type', 'Excerpt');

        return $excerptBlock ? true : false;
    }
}

if (!function_exists('get_excerpt')) {
    function get_excerpt($content, $limit = 300)
    {
        $blocks = $content->blocks;
        $excerptBlock = $blocks->firstWhere('type', 'Excerpt');

        return $excerptBlock ? mb_strimwidth(strip_tags($excerptBlock->content), 0, $limit, '...') : null;
    }
}

if (!function_exists('has_text_block')) {
    function has_text_block($content)
    {
        $blocks = $content->blocks;
        $textBlock = $blocks->firstWhere('type', 'Text');
        if(is_null($textBlock))
            $textBlock = $blocks->firstWhere('type', 'Text');

        return $textBlock ? true : false;
    }
}

if (!function_exists('get_text_block')) {
    function get_text_block($content)
    {
        $blocks = $content->blocks;
        $textBlock = $blocks->firstWhere('type', 'Text');
        if(is_null($textBlock))
            $textBlock = $blocks->firstWhere('type', 'Text');

        return $textBlock ? ($textBlock->content) : null;
    }
}

if (!function_exists('get_seo_image')) {
    function get_seo_image($content)
    {
        if($content->featuredimage)
            return $content->featuredimage->large;

        $blocks = $content->blocks;
        $imageBlock = $blocks->firstWhere('type', 'Image');
        if(!is_null($imageBlock)) {
            $content = $imageBlock->content;
            $image = $content && $content->path .  $content->filename . '.' .  $content->extension;
        }

        return $imageBlock ? $image : null;
    }
}

if (!function_exists('get_seo_description')) {
    function get_seo_description($content, $default = null)
    {
        if($content->seo['metaDescription'])
            return $content->seo['metaDescription'];
        else
            return $default;
    }
}

/**
 * Check if specific widget group is visible
 */
if (!function_exists('is_widget_group_visible')) {
    function is_widget_group_visible($widget, $contentTypeId, $contentId = null)
    {
        $widgetService = new \App\Services\WidgetService();
        if($widget && $widgetService->isVisible($widget, $contentTypeId, $contentId))
            return $widget;
        else
            return null;
    }
}

/**
 * Returns the first widget registered for theme area
 */
if (!function_exists('get_widget_group')) {
    function get_widget_group($location)
    {
        $widget = WidgetGroup::with(['widgets' => function ($query) {
            $query->orderBy('order', 'asc');
        }])->where('location', $location)->first();

        return $widget;
    }
}

/**
 * Returns the first widget registered for theme area
 */
if (!function_exists('get_widgets')) {
    function get_widgets($widgetGroup)
    {
        return process_widgets($widgetGroup->widgets);
    }
}

/**
 * processes widget blocks for rendering
 */
if (!function_exists('process_widgets')) {
    function process_widgets($rawWidgets)
    {
        $widgetsIds = array();
        $widgets = array();

        $rawWidgets = $rawWidgets->sortBy('parent_id');

        foreach ($rawWidgets as $key => $rawWidget) {
            $rawWidget->subItems = array();
        }

        foreach ($rawWidgets as $key => $widget) {
            $widgets[$widget->unique_id] = $widget;
            if ($widget->parent_id) {
                $parent = $widgets[$widget->parent_id];
                $subs = $parent->subItems;
                array_push($subs, $widget->unique_id);
                $parent->subItems = $subs;
            } else {
                $widgetsIds[$widget->unique_id] = $widget->unique_id;
            }
        }

        return array($widgets, $widgetsIds);
    }
}

/**
 * returns user created menu by location
 */
if (!function_exists('get_menu')) {
    function get_menu($location)
    {
        // $menu = \Cache::rememberForever('menu-location-'.$location, function() use($location) {
        // });

        $menu = \Menu::where('location', 'header')->with(array('items'=>function($query) {
            $query->where('parent_id', null)->with('subItems.subItems');
        }))->first();

        return $menu ? $menu->items : null;
    }
}

/**
 * returns user created menu by location
 */
if (!function_exists('get_menu_by_id')) {
    function get_menu_by_id($id)
    {
        \Cache::flush();
        $menu = \Cache::rememberForever('menu-id-'.$id, function() use($id) {
            return \Menu::where('id', $id)->with('items')->first();
        });

        return $menu ? $menu->items : null;
    }
}

/**
 * Returns active theme folder
 */
if (!function_exists('get_theme_folder')) {
    function get_theme_folder()
    {
        $globalSettings = App\Services\GlobalSettingsService::class;
        $folder = app($globalSettings)->get($globalSettings::THEME_FOLDER);

        return $folder ? strtolower($folder) : config('laraone.default_theme.name');
    }
}

/**
 * Returns specified theme setting, settings are loaded at Laravel bootup time and kept in memory for quicker access
 */
if (!function_exists('get_theme_setting')) {
    function get_theme_setting($setting, $default = null)
    {
        $globalSettings = App\Services\GlobalSettingsService::class;
        $settings = app($globalSettings)->get($globalSettings::THEME_SETTINGS);
        $data = data_get($settings, $setting);

        return (!is_null($data)) ? $data : $default;
    }
}

/**
 * Returns specified theme setting, settings are loaded at Laravel bootup time and kept in memory for quicker access
 */
if (!function_exists('get_theme_timestamp')) {
    function get_theme_timestamp()
    {
        $theme = \App\Models\Core\Design\Theme::where('folder', get_theme_folder())->first();
        return $theme ? $theme->updated_at->timestamp : null;
    }
}

/**
 * Returns specified website setting, loaded at boot time, kept in memory for quicker access
 * @param string $setting key to look up
 * @return Value that is stored in database for that particular setting
 */
if (!function_exists('get_website_setting')) {
    function get_website_setting($setting, $default = null)
    {
        $globalSettings = App\Services\GlobalSettingsService::class;
        $settings = app($globalSettings)->get($globalSettings::WEBSITE_SETTINGS);
        $data = data_get($settings, $setting);

        return (!is_null($data)) ? $data : $default;
    }
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source https://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 100, $d = 'robohash', $r = 'g', $img = false, $atts = array() ) {

    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}


/**
 * Returns specified theme setting, settings are loaded at Laravel bootup time and kept in memory for quicker access
 */
if (!function_exists('get_taxonomy')) {
    function get_taxonomy($contentTypeId = 3, $taxonomySlug = 'types')
    {
        $taxonomy = \App\Models\Core\Taxonomies\Taxonomy::with('terms')->where('content_type_id', $contentTypeId)->where('slug', $taxonomySlug)->first();
        return $taxonomy ? $taxonomy : null;
    }
}

/**
 * Returns specified theme setting, settings are loaded at Laravel bootup time and kept in memory for quicker access
 */
if (!function_exists('get_taxonomy_terms')) {
    function get_taxonomy_terms($contentTypeId = 3, $taxonomySlug = 'types')
    {
        $taxonomy = \App\Models\Core\Taxonomies\Taxonomy::with('terms')->where('content_type_id', $contentTypeId)->where('slug', $taxonomySlug)->first();
        return $taxonomy ? $taxonomy->terms : null;
    }
}
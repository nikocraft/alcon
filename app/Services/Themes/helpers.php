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
        if(is_null($excerptBlock))
            $excerptBlock = $blocks->firstWhere('type', 'Text');

        return $excerptBlock ? true : false;
    }
}

if (!function_exists('get_excerpt')) {
    function get_excerpt($content, $limit = 300)
    {
        $blocks = $content->blocks;
        $excerptBlock = $blocks->firstWhere('type', 'Excerpt');
        if(is_null($excerptBlock))
            $excerptBlock = $blocks->firstWhere('type', 'Text');

        return $excerptBlock ? mb_strimwidth(strip_tags($excerptBlock->content), 0, $limit, '...') : null;
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
    function get_seo_description($content)
    {
        if($content->seo['metaDescription'])
            return $content->seo['metaDescription'];
        else
            return "todo: get website default description from websiteSettings";
    }
}

/**
 * processes widget blocks for rendering
 */
if (!function_exists('process_widget_blocks')) {
    function process_widget_blocks($blocks)
    {
        $blockIdsList = array();
        $widgetBlockList = array();

        foreach ($blocks as $key => $block) {
            $block->subItems = array();
        }

        foreach ($blocks as $key => $block) {
            $widgetBlockList[$block->unique_id] = $block;
            if ($block->parent_id) {
                $parent = $widgetBlockList[$block->parent_id];
                $subs = $parent->subItems;
                array_push($subs, $block->unique_id);
                $parent->subItems = $subs;
            } else {
                $blockIdsList[$block->unique_id] = $block->unique_id;
            }
        }

        return array($widgetBlockList, $blockIdsList);
    }
}

/**
 * Return multiple widgets registered with same theme area
 */
// if (!function_exists('get_widgets_by_area')) {
//     function get_widgets_by_area($widgets, $area)
//     {
//         return $widgets->where('theme_area', $area);
//     }
// }

/**
 * Returns the first widget registered for theme area
 */
if (!function_exists('get_widget_by_area')) {
    function get_widget_by_area($widgets, $area, $contentTypeId, $contentId = null)
    {
        $widgetService = new \App\Services\WidgetService();
        $widget = $widgets->firstWhere('theme_area', $area);
        if($widget && $widgetService->isVisible($widget, $contentTypeId, $contentId))
            return $widget;
        else
            return null;
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
 * Returns specified theme setting
 */
if (!function_exists('get_theme_folder')) {
    function get_theme_folder()
    {
        $globalSettings = App\Services\GlobalSettingsService::class;
        $folder = app($globalSettings)->get($globalSettings::THEME_FOLDER);

        return $folder ? $folder : $globalSettings::DEFAULT_THEME;
    }
}


/**
 * Returns specified theme setting
 */
if (!function_exists('get_theme_setting')) {
    function get_theme_setting($setting)
    {
        $globalSettings = App\Services\GlobalSettingsService::class;
        $settings = app($globalSettings)->get($globalSettings::THEME_SETTINGS);
        $data = data_get($settings, $setting);

        return $data ? $data : null;
    }
}

/**
 * Returns specified website setting
 * @param string $setting key to look up
 * @return Value that is stored in database for that particular setting
 */
if (!function_exists('get_website_setting')) {
    function get_website_setting($setting)
    {
        $globalSettings = App\Services\GlobalSettingsService::class;
        $settings = app($globalSettings)->get($globalSettings::WEBSITE_SETTINGS);
        $data = data_get($settings, $setting);

        return $data ? $data : null;
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

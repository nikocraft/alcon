<?php

$baseUrl = 'https://github.com/laraone/';
$defaultTheme = 'ikigai';

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Spa
    |--------------------------------------------------------------------------
    */
    'admin_theme' => [
        'name' => 'admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Theme Urls
    |--------------------------------------------------------------------------
    |
    | Used by install and update commands to fetch default frontend theme for Laraone
    / Note: This is not necessary active theme
    */
    'default_theme' => [
        'releases_url' => env('DEFAULT_THEME_RELEASES', $baseUrl . $defaultTheme .'/raw/master/releases.json'),
        'download_url' => env('DEFAULT_THEME_DOWNLOAD', $baseUrl . $defaultTheme. '/releases/download'),
        'name' => env('DEFAULT_THEME_NAME', $defaultTheme),
    ],
];

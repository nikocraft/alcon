<?php

$baseUrl = 'https://github.com/laraone/';
$adminTheme = 'atlas';
$defaultTheme = 'ikigai';

return [
    /*
    |--------------------------------------------------------------------------
    | Phoenix Urls
    |--------------------------------------------------------------------------
    |
    | Used by install and update commands to fetch latest backend for Laraone
    */
    'phoenix' => [
        'releases_url' => env('PHOENIX_RELEASES', $baseUrl . 'phoenix/raw/master/releases.json'),
        'download_url' => env('PHOENIX_DOWNLOAD', $baseUrl . 'phoenix/archive/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Spa Urls
    |--------------------------------------------------------------------------
    |
    | Used by install and update commands to fetch latest admin spa for Laraone
    */
    'admin_theme' => [
        'releases_url' => env('ATLAS_RELEASES', $baseUrl . 'atlas/raw/master/releases.json'),
        'download_url' => env('ATLAS_DOWNLOAD', $baseUrl . 'atlas/releases/download'),
        'name' => 'atlas',
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

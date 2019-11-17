<?php

$baseUrl = 'https://github.com/laraone/';
$adminTheme = 'atlas';

$themeBaseUrl = 'https://github.com/laraone/';

// default theme to be installed
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
        'releases_url' => $baseUrl . 'phoenix/raw/master/releases.json',
        'download_url' => $baseUrl . 'phoenix/archive/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Spa Urls
    |--------------------------------------------------------------------------
    |
    | Used by install and update commands to fetch latest admin spa for Laraone
    */
    // 'admin_releases_url' => $baseUrl . 'admin-releases/raw/master/releases.json',
    // 'admin_download_url' => $baseUrl . 'admin-releases/releases/download',
    // 'admin_theme' => 'admin',

    'admin_theme' => [
        'releases_url' => $baseUrl . 'admin-releases/raw/master/releases.json',
        'download_url' => $baseUrl . 'admin-releases/releases/download',
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
        'releases_url' => $themeBaseUrl . $defaultTheme .'/raw/master/releases.json',
        'download_url' => $themeBaseUrl . $defaultTheme. '/releases/download',
        'name' => $defaultTheme,
    ],
];

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Phoenix Urls
    |--------------------------------------------------------------------------
    |
    | Used by install and update commands to fetch latest backend for Laraone
    */
    'phoenix_releases_url' => 'https://github.com/laraone/phoenix/raw/master/releases.json',
    'phoenix_download_url' => 'https://github.com/laraone/phoenix/archive/',
    // 'phoenix_releases_download_url' => 'https://github.com/laraone/phoenix/releases/download',

    /*
    |--------------------------------------------------------------------------
    | Admin Spa Urls
    |--------------------------------------------------------------------------
    |
    | Used by install and update commands to fetch latest admin spa for Laraone
    */
    'admin_releases_url' => 'https://github.com/laraone/admin-releases/raw/master/releases.json',
    'admin_download_url' => 'https://github.com/laraone/admin-releases/releases/download',
    'admin_file_name' => 'admin.zip',


    /*
    |--------------------------------------------------------------------------
    | Default Theme Urls
    |--------------------------------------------------------------------------
    |
    | Used by install and update commands to fetch default frontend theme for Laraone
    */
    'default_theme_releases_url' => 'https://github.com/laraone/ikigai-theme-releases/raw/master/releases.json',
    'default_theme_download_url' => 'https://github.com/laraone/ikigai-theme-releases/releases/download',
    'default_theme_file_name' => 'ikigai.zip',
];

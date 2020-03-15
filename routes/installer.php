<?php

/*
|--------------------------------------------------------------------------
| Laraone CMS Installer Routes
|--------------------------------------------------------------------------
| 
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
| 
*/
Route::group(['middleware' => 'installationGuard'], function () {
    Route::get('/', 'InstallerController@showInstallWizard');
    Route::post('environment-settings', ['as' => 'install.save-environment-settings', 'uses' => 'InstallerController@saveEnvironmentSettings']);
    Route::post('site-settings', ['as' => 'install.save-site-settings', 'uses' => 'InstallerController@saveSiteSettings']);
    Route::post('install', ['as' => 'install', 'uses' => 'InstallerController@runInstaller']);
});
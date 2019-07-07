<?php

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'auth', 'middleware'=>'setTheme:admin_one', 'namespace' => 'Backend'], function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showAuthForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\LoginController@showAuthForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset.post');

    Route::get('/activate/{user}', 'Auth\ActivateController@showActivate')->name('backend.user.activate.show');
    Route::post('/activate/{user}', 'Auth\ActivateController@activate')->name('backend.user.activate');
    // Route::post('/activate/{user}', 'Auth\ActivateController@activate')->name('backend.user.activate')->middleware('urldecode');
});
<?php

use Illuminate\Http\Request;
use App\Models\Core\Content\ContentType;

/*
|--------------------------------------------------------------------------
| Localization route, this is WIP
|--------------------------------------------------------------------------
*/
Route::get('/js/lang.js', function () {
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');
        $files   = glob(resource_path('lang/' . $lang . '/components/*.php'));
        $strings = [];
        foreach ($files as $file) {
            $name           = basename($file, '.php');
            $strings[$name] = require $file;
        }
        return $strings;
    });
    $javascript = 'window.i18n = ' . json_encode($strings) . ';';
    return response($javascript, 200, [
        'Cache-Control' => 'max-age=3600, public',
        'Last-Modified' => Carbon\Carbon::now(),
        'Content-Length' => strlen($javascript),
        'Content-Type' => 'text/javascript'
    ]);
})->name('assets.lang');


/*
|--------------------------------------------------------------------------
| SPA entry point route
|--------------------------------------------------------------------------
|
*/
Route::group(['namespace' => 'Backend\Spa', 'middleware' => ['auth', 'setTheme:admin_one', 'ability:super,access-admin']], function () {
    Route::get('admin{any}', ['as' => 'backend.spa', 'uses' => 'SpaController@index'])->where('any', '.*');
});

$activeThemeFolder = get_theme_folder();
$activeThemeFolder = $activeThemeFolder ? $activeThemeFolder : "ikigai";


/*
|--------------------------------------------------------------------------
| Front-End routes, routes hit by visitors of the website
|--------------------------------------------------------------------------
|
*/
Route::group(['namespace' => 'Frontend\Core', 'middleware'=>'setTheme:'.$activeThemeFolder], function () {
    Route::group(['namespace' => 'Content'], function () {
        /*--------------------------------------------------------------------------
        | Repeatable content type routes, for example Posts, Projects, etc
        |--------------------------------------------------------------------------*/
        $contentTypes = ContentType::whereType(2)->with('taxonomies')->get();
        foreach ($contentTypes as $contentType) {
            foreach ($contentType->taxonomies as $taxonomy) {
                Route::get($taxonomy->slug.'/{term}', ['as' => 'frontend.'.$contentType->front_slug.'.'.$taxonomy->slug.'.category','uses' => 'ContentController@taxonomy'])->defaults('taxonomyId', $taxonomy->id);
            }

            Route::get($contentType->front_slug, [ 'as' => 'frontend.'.$contentType->front_slug.'.index', 'uses' => 'ContentController@index'])->defaults('contentTypeId', $contentType->id);
            Route::get($contentType->front_slug.'/{slug}', ['as' => 'frontend.'.$contentType->front_slug.'.show','uses' => 'ContentController@show'])->defaults('contentTypeId', $contentType->id);
        }

        /*--------------------------------------------------------------------------
        | Non-repeatable content type routes, Pages
        |--------------------------------------------------------------------------*/
        $pagesContentTypes = ContentType::whereType(1)->with('taxonomies')->first();
        if ($pagesContentTypes) {
            Route::get('/', 'ContentController@frontPage');
            Route::get('{slug}', ['as' => 'frontend.page.show', 'uses' => 'ContentController@show'])->defaults('contentTypeId', $pagesContentTypes->id);
        }
    });

    // Route::get('/activate-user/{user}', 'Core\Users\ActivateController@activate'))->name('frontend.user.activate.show');
});

Route::group(['prefix' => 'api', 'namespace' => 'Frontend\Core'], function () {
    Route::group(['namespace' => 'Comments'], function () {
        Route::get('comments/{content}', ['as' => 'frontend.comments.index', 'uses' => 'CommentController@index']);
        Route::post('comment', ['as' => 'frontend.comments.store', 'uses' => 'CommentController@store']);
    });
});

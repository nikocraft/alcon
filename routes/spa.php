<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Laraone CMS SPA Routes
|--------------------------------------------------------------------------
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "web" middleware group.
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'Backend\Spa', 'as' => 'api.', 'middleware' => ['auth', 'ability:super,access-admin']], function () {

    Route::group(['namespace' => 'Content', 'as' => 'content-type.', 'prefix' => 'content-type'], function () {
        Route::get('list', 'ContentTypeController@list')->name('list');
        Route::get('{contentTypeId}', 'ContentTypeController@show')->name('show');
    });

    Route::group(['namespace' => 'Content', 'as' => 'content.', 'prefix' => 'content', 'middleware' => ['ability:super,manage-content']], function () {
        Route::get('tree-data', 'ContentController@getTreeData')->name('tree.data');

        Route::get('index-settings', 'ContentSettingsController@showIndexSettings')->name('index.settings');
        Route::post('index-settings', 'ContentSettingsController@storeIndexSettings')->name('index.settings');
        Route::post('editor-settings', 'ContentSettingsController@storeEditorSettings')->name('editor.settings');

        // featured image
        Route::post('set-featured-image','ContentController@setFeaturedImage')->name('set.featuredimage');
        Route::post('remove-featured-image', 'ContentController@removeFeaturedImage')->name('remove.featuredimage');

        /** Editor Init Data */
        Route::get('{contentTypeId}/init-data', 'ContentController@getInitData')->name('editor.init-data');

        /** Content Routes */
        Route::get('{contentTypeId}', 'ContentController@index')->name('index');
        Route::post('{contentTypeId}', 'ContentController@store')->name('store');
        Route::get('{contentTypeId}/{id}', 'ContentController@show')->name('show');
        Route::patch('{contentTypeId}/{id}', 'ContentController@update')->name('update');
        Route::delete('{contentTypeId}/{id}', 'ContentController@destroy')->name('destroy');

        /** TAXONOMY ROUTE */
        Route::get('{contentTypeId}/taxonomy/{taxonomyId}', 'TaxonomyController@show')->name('taxonomy.show');

        /** TERMS ROUTES */
        Route::resource('taxonomy/terms', 'TermController', ['as' => 'taxonomy'])->only(['store', 'destroy']);
    });

    /** MEDIA ROUTES */
    Route::group(['namespace' => 'Media', 'as' => 'media.', 'prefix' => 'media', 'middleware' => ['ability:super,access-media']], function () {
        Route::group(['middleware' => ['ability:super,manage-images']], function() {
            Route::post('upload', ['as' => 'upload', 'uses' => 'MediaController@upload']);
            Route::get('tags', ['as' => 'images.tags', 'uses' => 'ImageController@tags']);
            Route::apiResource('images', 'ImageController')->only(['index', 'show', 'update', 'destroy']);
        });
    });

    /** ACL ROUTES */
    Route::group(['namespace' => 'Users', 'middleware' => ['ability:super,access-acl']], function () {
        // Users
        Route::group(['middleware' => ['ability:super,manage-users']], function() {
            Route::get('getallroles', ['as' => 'users.getallroles', 'uses' => 'UserController@getAllRoles']);
            Route::apiResource('users', 'UserController')->only(['index', 'show', 'store', 'update', 'destroy']);
        });

        // Roles
        Route::group(['middleware' => ['ability:super,manage-roles']], function() {
            Route::post('roles/unique', ['as' => 'roles.unique', 'uses' => 'RoleController@unique']);
            Route::apiResource('roles', 'RoleController')->only(['index', 'show', 'store', 'update', 'destroy']);
        });
    });

    /** COMMENTS ROUTES */
    Route::group(['middleware' => ['ability:super,manage-comments']], function() {
        Route::apiResource('comments', 'Comments\CommentController')->only(['index', 'update', 'destroy']);
    });

    Route::group(['namespace' => 'Design', 'as' => 'design.', 'prefix' => 'design', 'middleware' => ['ability:super,access-design']], function () {
        /** TEMPLATE ROUTES */
        Route::post('templates/upload-screenshot', 'TemplateController@uploadScreenshot')->name('templates.uploadscreenshot');
        Route::get('templates/tags', ['as' => 'templates.tags', 'uses' => 'TemplateController@tags']);
        Route::apiResource('templates', 'TemplateController')->only(['index', 'show', 'store']);

        /** THEMES ROUTES */
        Route::post('themes/set-active', 'ThemeController@setActive')->name('themes.set-active');
        Route::post('themes/upload', 'ThemeController@upload')->name('themes.upload');
        Route::get('themes/get-active', 'CustomizeController@getActive')->name('themes.get-active');
        Route::post('themes/update-active', 'CustomizeController@updateActive')->name('themes.update-active');
        Route::apiResource('themes', 'ThemeController')->only(['index']);

        /** MENU ROUTES */
        Route::apiResource('menus', 'MenuController')->only(['index', 'show', 'store', 'destroy']);
        Route::get('locations', 'MenuController@locations')->name('menus.locations');

        /** WIDGET ROUTES */
        Route::get('widgets/areas', ['as' => 'widgets.areas', 'uses' => 'WidgetController@getAreas']);
        Route::get('widgets/get-content-tree', 'WidgetController@getContentTree')->name('widgets.get-content-tree');
        Route::apiResource('widgets', 'WidgetController')->only(['index', 'show', 'store', 'destroy']);
    });

    /** SETTINGS */
    Route::group(['namespace' => 'Settings', 'as' => 'settings.', 'prefix' => 'settings', 'middleware' => ['ability:super,access-settings']], function () {
        Route::resource('website', 'WebsiteController')->only(['index', 'store']);
        Route::resource('backend', 'AdminController')->only(['index', 'store']);
        Route::resource('login', 'LoginController')->only(['index', 'store']);
        Route::resource('members', 'MembersController')->only(['index', 'store']);
        Route::resource('comments', 'CommentsController')->only(['index', 'store']);

        /** Only super admins (developers) should attempt to mess with this feature */
        Route::group(['middleware' => ['role:super']], function() {
            Route::resource('content-types', 'ContentTypeController')->only(['index', 'store', 'show', 'update', 'destroy']);
        });
        // Route::resource('favorites', 'FavoritesController')->only(['index', 'store']);
        Route::resource('urls', 'UrlsController')->only(['index', 'store']);
    });
});

<?php

use Illuminate\Support\Str;
use PickOne\Hymer\Events\Routing;
use PickOne\Hymer\Events\RoutingAdmin;
use PickOne\Hymer\Events\RoutingAdminAfter;
use PickOne\Hymer\Events\RoutingAfter;
use PickOne\Hymer\Facades\Hymer;

/*
|--------------------------------------------------------------------------
| Hymer Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with Hymer.
|
*/

Route::group(['as' => 'hymer.'], function () {
    event(new Routing());

    $namespacePrefix = '\\'.config('hymer.controllers.namespace').'\\';

    Route::get('login', ['uses' => $namespacePrefix.'HymerAuthController@login',     'as' => 'login']);
    Route::post('login', ['uses' => $namespacePrefix.'HymerAuthController@postLogin', 'as' => 'postlogin']);

    Route::group(['middleware' => 'admin.user'], function () use ($namespacePrefix) {
        event(new RoutingAdmin());

        // Main Admin and Logout Route
        Route::get('/', ['uses' => $namespacePrefix.'HymerController@index',   'as' => 'dashboard']);
        Route::post('logout', ['uses' => $namespacePrefix.'HymerController@logout',  'as' => 'logout']);
        Route::post('upload', ['uses' => $namespacePrefix.'HymerController@upload',  'as' => 'upload']);

        Route::get('profile', ['uses' => $namespacePrefix.'HymerUserController@profile', 'as' => 'profile']);

        try {
            foreach (Hymer::model('DataType')::all() as $dataType) {
                $breadController = $dataType->controller
                                 ? Str::start($dataType->controller, '\\')
                                 : $namespacePrefix.'HymerBaseController';

                Route::get($dataType->slug.'/order', $breadController.'@order')->name($dataType->slug.'.order');
                Route::post($dataType->slug.'/action', $breadController.'@action')->name($dataType->slug.'.action');
                Route::post($dataType->slug.'/order', $breadController.'@update_order')->name($dataType->slug.'.update_order');
                Route::get($dataType->slug.'/{id}/restore', $breadController.'@restore')->name($dataType->slug.'.restore');
                Route::get($dataType->slug.'/relation', $breadController.'@relation')->name($dataType->slug.'.relation');
                Route::post($dataType->slug.'/remove', $breadController.'@remove_media')->name($dataType->slug.'.media.remove');
                Route::resource($dataType->slug, $breadController, ['parameters' => [$dataType->slug => 'id']]);
            }
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
        } catch (\Exception $e) {
            // do nothing, might just be because table not yet migrated.
        }

        // Menu Routes
        Route::group([
            'as'     => 'menus.',
            'prefix' => 'menus/{menu}',
        ], function () use ($namespacePrefix) {
            Route::get('builder', ['uses' => $namespacePrefix.'HymerMenuController@builder',    'as' => 'builder']);
            Route::post('order', ['uses' => $namespacePrefix.'HymerMenuController@order_item', 'as' => 'order_item']);

            Route::group([
                'as'     => 'item.',
                'prefix' => 'item',
            ], function () use ($namespacePrefix) {
                Route::delete('{id}', ['uses' => $namespacePrefix.'HymerMenuController@delete_menu', 'as' => 'destroy']);
                Route::post('/', ['uses' => $namespacePrefix.'HymerMenuController@add_item',    'as' => 'add']);
                Route::put('/', ['uses' => $namespacePrefix.'HymerMenuController@update_item', 'as' => 'update']);
            });
        });

        // Settings
        Route::group([
            'as'     => 'settings.',
            'prefix' => 'settings',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'HymerSettingsController@index',        'as' => 'index']);
            Route::post('/', ['uses' => $namespacePrefix.'HymerSettingsController@store',        'as' => 'store']);
            Route::put('/', ['uses' => $namespacePrefix.'HymerSettingsController@update',       'as' => 'update']);
            Route::delete('{id}', ['uses' => $namespacePrefix.'HymerSettingsController@delete',       'as' => 'delete']);
            Route::get('{id}/move_up', ['uses' => $namespacePrefix.'HymerSettingsController@move_up',      'as' => 'move_up']);
            Route::get('{id}/move_down', ['uses' => $namespacePrefix.'HymerSettingsController@move_down',    'as' => 'move_down']);
            Route::put('{id}/delete_value', ['uses' => $namespacePrefix.'HymerSettingsController@delete_value', 'as' => 'delete_value']);
        });

        // Admin Media
        Route::group([
            'as'     => 'media.',
            'prefix' => 'media',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'HymerMediaController@index',              'as' => 'index']);
            Route::post('files', ['uses' => $namespacePrefix.'HymerMediaController@files',              'as' => 'files']);
            Route::post('new_folder', ['uses' => $namespacePrefix.'HymerMediaController@new_folder',         'as' => 'new_folder']);
            Route::post('delete_file_folder', ['uses' => $namespacePrefix.'HymerMediaController@delete', 'as' => 'delete']);
            Route::post('move_file', ['uses' => $namespacePrefix.'HymerMediaController@move',          'as' => 'move']);
            Route::post('rename_file', ['uses' => $namespacePrefix.'HymerMediaController@rename',        'as' => 'rename']);
            Route::post('upload', ['uses' => $namespacePrefix.'HymerMediaController@upload',             'as' => 'upload']);
            Route::post('crop', ['uses' => $namespacePrefix.'HymerMediaController@crop',             'as' => 'crop']);
        });

        // BREAD Routes
        Route::group([
            'as'     => 'bread.',
            'prefix' => 'bread',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'HymerBreadController@index',              'as' => 'index']);
            Route::get('{table}/create', ['uses' => $namespacePrefix.'HymerBreadController@create',     'as' => 'create']);
            Route::post('/', ['uses' => $namespacePrefix.'HymerBreadController@store',   'as' => 'store']);
            Route::get('{table}/edit', ['uses' => $namespacePrefix.'HymerBreadController@edit', 'as' => 'edit']);
            Route::put('{id}', ['uses' => $namespacePrefix.'HymerBreadController@update',  'as' => 'update']);
            Route::delete('{id}', ['uses' => $namespacePrefix.'HymerBreadController@destroy',  'as' => 'delete']);
            Route::post('relationship', ['uses' => $namespacePrefix.'HymerBreadController@addRelationship',  'as' => 'relationship']);
            Route::get('delete_relationship/{id}', ['uses' => $namespacePrefix.'HymerBreadController@deleteRelationship',  'as' => 'delete_relationship']);
        });

        // Database Routes
        Route::resource('database', $namespacePrefix.'HymerDatabaseController');

        // Compass Routes
        Route::group([
            'as'     => 'compass.',
            'prefix' => 'compass',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'HymerCompassController@index',  'as' => 'index']);
            Route::post('/', ['uses' => $namespacePrefix.'HymerCompassController@index',  'as' => 'post']);
        });

        event(new RoutingAdminAfter());
    });

    //Asset Routes
    Route::get('hymer-assets', ['uses' => $namespacePrefix.'HymerController@assets', 'as' => 'hymer_assets']);

    event(new RoutingAfter());
});

<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
* Snippet for a quick route reference
*/
Route::get('/', function (Router $router) {
    return collect($router->getRoutes()->getRoutesByMethod()["GET"])->map(function($value, $key) {
        return url($key);
    })->values();   
});

// Get products for the authenticated user
Route::get('products/user', 'ProductAPIController@showForAuthenticatedUser');
Route::post('products/{product}/attach', 'ProductAPIController@attach');
Route::post('products/{product}/detach', 'ProductAPIController@detach');
Route::post('products/{product}/upload-image', 'ProductAPIController@uploadImage');
Route::resource('products', 'ProductAPIController', [
    'only' => ['index', 'show', 'store', 'update', 'destroy']
]);

Route::resource('users', 'UserAPIController', [
    'only' => ['index', 'show', 'store', 'update', 'destroy']
]);
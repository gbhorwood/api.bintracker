<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Group routes by controllers.
| Put routes that require a session in an 'auth:api' block
| Put routes that require an admin session in an 'auth:api' and 'admin_only' block
| Use full endpoints and full namespaces for controllers
| The `helpers/showroutes.sh` script provides a nice output of this file
|
| show all routes with:
| helpers/showroutes.sh
*/


/*
|--------------------------------------------------------------------------
| Version
|--------------------------------------------------------------------------
*/
Route::get('/version', function (Request $request) {
    return file_get_contents(base_path()."/version");
});

/*
|--------------------------------------------------------------------------
| Passport
|--------------------------------------------------------------------------
*/
Route::post('register', '\App\Http\Controllers\api\RegisterController@register');
Route::post('login', '\App\Http\Controllers\api\RegisterController@login');

/*
|--------------------------------------------------------------------------
| Items
|--------------------------------------------------------------------------
*/
Route::get('items/units', '\App\Http\Controllers\api\ItemController@getUnits');
Route::middleware(['auth:api'])->group(function () {
    Route::get('items', '\App\Http\Controllers\api\ItemController@getItems');
    Route::post('items', '\App\Http\Controllers\api\ItemController@postItems');
    Route::delete('items/{id}', '\App\Http\Controllers\api\ItemController@deleteItems');
});

/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api'])->group(function () {
    Route::get('categories/digest', '\App\Http\Controllers\api\CategoryController@getCategoriesDigest');
    Route::get('categories/{id}/items', '\App\Http\Controllers\api\CategoryController@getCategoryItems');
});

/*
|--------------------------------------------------------------------------
| CategoryItems
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api'])->group(function () {
    Route::put('categories/{categoryId}/items/{itemId}', '\App\Http\Controllers\api\CategoryItemController@putCategoryItem');
    Route::delete('categories/{categoryId}/items/{itemId}', '\App\Http\Controllers\api\CategoryItemController@deleteCategoryItem');
});

/*
|--------------------------------------------------------------------------
| Bins
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api'])->group(function () {
    Route::post('bins', '\App\Http\Controllers\api\BinController@postBins');
    Route::get('bins/digest', '\App\Http\Controllers\api\BinController@getBinsDigest');
    Route::get('bins/{id}/items', '\App\Http\Controllers\api\BinController@getBinItems');
});

/*
|--------------------------------------------------------------------------
| BinItems
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api'])->group(function () {
    Route::put('bins/{binId}/items/{itemId}', '\App\Http\Controllers\api\BinItemController@putBinItem');
    Route::delete('binitems/{id}', '\App\Http\Controllers\api\BinItemController@deleteBinItem');
});

/*
|--------------------------------------------------------------------------
| Admin
| admin routes should be guarded by middleware 'admin_only'
| @see app/Http/Middleware/AdminOnly.php
| @see app/Exceptions/Handler.php render()
| @see app/Http/Kernel.php routeMiddleware
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'admin_only'])->group(function () {
    Route::get('admin/users', '\App\Http\Controllers\api\Admin\AdminController@getUsers');
    Route::post('admin/users', '\App\Http\Controllers\api\Admin\AdminController@postUsers');
});

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin', 'namespace' => '\Arukomp\Bloggy\Http\Controllers\Admin', 'middleware' => ['web', 'auth']], function () {
    Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');

    Route::resource('postTypes', 'PostTypesController', ['as' => 'admin']);
    Route::resource('trashedPostTypes', 'TrashedPostTypesController', ['as' => 'admin']);
    
    Route::resource('postType.posts', 'PostsController', ['as' => 'admin', 'only' => ['index', 'create', 'store']]);
    Route::resource('posts', 'PostsController', ['as' => 'admin', 'only' => ['edit', 'update', 'destroy']]);
    Route::resource('postType.trashedPosts', 'TrashedPostsController', ['as' => 'admin', 'only' => ['index']]);

    Route::get('postTypes/{id}/restore', 'PostTypesController@restore')->name('admin.postTypes.restore');

    Route::get('posts/{post}/unpublish', 'PostsController@unpublish')->name('admin.posts.unpublish');
    Route::get('posts/{post}/publish', 'PostsController@publish')->name('admin.posts.publish');
    Route::put('posts/{post}/restore', 'PostsController@restore')->name('admin.posts.restore');
});

Route::group(['namespace' => '\Arukomp\Bloggy\Http\Controllers', 'middleware' => ['web']], function () {
    Route::get('/{postType}/{post}', 'PostsController@show')->name('posts.show');
});

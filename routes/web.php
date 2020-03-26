<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

# Authentication
Auth::routes(['verify' => true]);

# Admin route
Route::prefix('admin')->name('admin.')->middleware(['role:admin', 'verified'])->group(function(){
    Route::get('/', function(){
        return redirect('/admin/dashboard');
    });
    Route::get('dashboard', 'AdminController@index');

    Route::group(['prefix' => 'posts'], function(){
        Route::get('{post_type}', 'PostController@index');
        Route::post('{post_type}/search', 'PostController@postSearch');
        Route::get('{post_type}/{action}/{post_id?}', 'PostController@getPost');
        Route::post('{post_type}/{action}/{post_id?}', 'PostController@postPost');
    });

    Route::group(['prefix' => 'media'], function(){
        Route::get('/', 'MediaController@getMedia');
        Route::post('/', 'MediaController@postMedia');
        Route::post('lazyload', 'MediaController@getMediaLazy');
        Route::post('media-filter', 'MediaController@getMediaFilter');
        Route::post('get-media', 'MediaController@getMediaAlone');
        Route::post('save-media', 'MediaController@postSaveMedia');
        Route::post('delete-media', 'MediaController@postDeleteMedia');
        Route::post('delete-multi-media', 'MediaController@postDeleteMultiMedia');
    });

    Route::group(['prefix' => 'taxonomy'], function(){
        Route::post('add-taxonomy-ajax', 'TaxonomyController@postTaxonomyAjax');
        Route::get('{taxonomy}', 'TaxonomyController@index');
        Route::post('{taxonomy}', 'TaxonomyController@postIndex');
        Route::get('{taxonomy}/edit/{term_taxonomy_id}', 'TaxonomyController@getEdit');
        Route::post('{taxonomy}/edit/{term_taxonomy_id}', 'TaxonomyController@postEdit');
        Route::get('{taxonomy}/delete/{term_taxonomy_id}', 'TaxonomyController@getDelete');
        Route::post('{taxonomy}/search', 'TaxonomyController@postSearch');
    });

    Route::name('users.')->prefix('users')->group(function(){
        Route::get('/{filter?}', 'UserController@index');
        Route::get('action/profile', 'UserController@getProfile');
        Route::post('action/profile', 'UserController@postProfile');
        Route::post('action/search', 'UserController@postSearch');
        Route::get('action/add', 'UserController@getAdd');
        Route::post('action/add', 'UserController@postAdd');
        Route::get('edit/{user_id}', 'UserController@getEdit')->name('edit');
        Route::post('edit/{user_id}', 'UserController@postEdit');
        Route::get('ban/{user_id}', 'UserController@getBan');
        Route::get('trash/{user_id}', 'UserController@getTrash');
        Route::get('restore/{user_id}', 'UserController@getRestore');
        Route::get('unblock/{user_id}', 'UserController@getUnblock');
        Route::get('order-history/{user_id}', 'UserController@orderHistory')->name('order_history');
    });
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home/{slug}', 'HomeController@postDetail')->name('post.detail');
Route::get('/home/tin-tuc/{slug}', 'HomeController@postCategories')->name('post.categories');

// Service routes
Route::get('tin-tuc', 'PostController@services')->name('list_post');
Route::get('danh-muc-tin-tuc/{slug}', 'PostController@serviceCategories')->name('post.categories12323');
Route::get('tin-tuc/{slug}', 'PostController@serviceDetail')->name('post.detail123');

<?php

use Illuminate\Support\Facades\Auth;
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

Route::group(['prefix' => 'admin', "namespace" => "AdminAuth", "as" => "admin."], function () {
    Route::get('/', function () {
        return redirect(route("admin.login"));
    });
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login')->name('login');
    Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
});

Route::group(['prefix' => 'admin', "middleware" => "auth:admin", "namespace" => "AdminAuth", "as" => "admin."], function () {
    Route::get('/change-password', 'ChangePasswordController@changePassword')->name('change-password.edit');
    Route::put('/change-password', 'ChangePasswordController@updatePassword')->name('change-password.update');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin', "namespace" => "Admin"], function () {
    Route::get('/', 'PageController@index')->name('admin.pages.home');
    // Categories
    Route::resource('/categories', 'CategoriesController');
    Route::get('/category/image-delete/{id}', 'CategoriesController@delete')->name('category.delete');

    // Image Utility
    Route::get('/image-gallery/selected', 'ImagesController@selectedImage')->name('image-gallery.selected');
    Route::get('/image-gallery/image/deselect', 'ImagesController@deselectImage')->name('image-gallery.deselect');
    Route::get('/pagination/fetch-data', 'ImagesController@fetchData')->name('pagination.fetch-data');
    Route::post('/upload-image', 'ImagesController@upload')->name('images.upload');
    Route::post('/delete-image', 'ImagesController@delete')->name('images.delete');

    // Types
    Route::resource('types', 'TypesController');
    Route::get('posts/create/{slug}', 'PostsController@create')->name('posts.create');
    Route::get('posts/{slug}', 'PostsController@index')->name('posts.index');
    Route::post('posts/{slug}', 'PostsController@store')->name('posts.store');
    Route::get('posts/{slug}/{id}/edit', 'PostsController@edit')->name('posts.edit');
    Route::put('posts/{slug}/{id}', 'PostsController@update')->name('posts.update');
    Route::delete('posts/{slug}/{id}', 'PostsController@destroy')->name('posts.destroy');
    Route::get('/types/image-delete/{id}/{postId}/{image}', 'PostsController@delete')->name('type.delete');

    // Contact Inquiries
    Route::get('/general-settings/create', "GeneralSettingController@create")->name("general-settings.create");
    Route::post('/general-settings', "GeneralSettingController@store")->name("general-settings.store");
    Route::get('/general-settings/edit', "GeneralSettingController@edit")->name("general-settings.edit");
    Route::put('/general-settings', "GeneralSettingController@update")->name("general-settings.update");
    Route::resource('contact-enquiries', 'ContactEnquiryController');
});

Route::get('/create-symlink', function () {
    $projectFolder = base_path() . '/../';
    // The file that you want to create a symlink of
    $source = $projectFolder . "/3jcRCPACuQtF5aKa/storage/app/public";
    // The path where you want to create the symlink of the above
    $destination = $projectFolder . "/storage";

    if (file_exists($destination)) {
        if (is_link($destination)) {
            return "<h1>Symlink already exists</h1>";
        }
    } else {
        symlink($source, $destination);
        return "<h1>Symlink created successfully</h1>";
    }
});

Route::get('/remove-symlink', function () {
    $projectFolder = base_path() . '/../';
    $destination = $projectFolder . "/storage";
    if (file_exists($destination)) {
        if (is_link($destination)) {
            unlink($destination);
            return "<h1>Removed symlink</h1>";
        }
    } else {
        return "<h1>Symlink does not exist</h1>";
    }
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

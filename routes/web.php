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

Route::get('/', 'HomeController@index')->name('home');


Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::post('/subscribe', 'SubscriberController@store')->name('subscriber.store');
Route::get('/post/{slug}', 'PostController@details')->name('post.index');
Route::get('/posts', 'PostController@index')->name('post.all');
Route::get('/category/{slug}', 'PostController@categoryByPosts')->name('category.posts');
Route::get('tags/{slug}', 'PostController@tagByPosts')->name('tag.posts');
Route::get('/search', 'SearchController@search')->name('search');
Route::get('/Profile/{author}','AuthorProfileController@index')->name('author.profile');


//Authenticate User Group Route
Route::group(['middleware'=>['auth']],function(){
    Route::post('favourite/{post}/add', 'FavouritePostController@add')->name('favourite.post');
    Route::post('comment/{post}', 'CommentController@store')->name('comment.store');
});

//Admin Group Route
Route::group(['as'=>'admin.','prefix'=>'admin','namespace'=>'Admin','middleware'=>['auth','admin']],function(){
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('tag', 'TagController');
    Route::resource('category', 'CategoryController');
    Route::resource('post', 'PostController');

    Route::get('authors', 'AuthorController@index')->name('authors');
    Route::delete('authors/{author}', 'AuthorController@destroy')->name('author.destroy');

    Route::get('/pending/post', 'PostController@pending')->name('post.pending');
    Route::put('/post/{id}/approve', 'PostController@approval')->name('post.approve');

    Route::get('/favourite', 'FavouriteController@index')->name('favourite.post');
    Route::delete('/favourite/{post}', 'FavouriteController@destroy')->name('favourite.destroy');

    Route::get('/comment', 'CommentController@index')->name('comment.index');
    Route::delete('/comment/{comment}', 'CommentController@destroy')->name('comment.destroy');
    
    Route::get('/subscribers', 'SubscriberController@index')->name('subscriber.index');
    Route::delete('/subscribers/{subscriber}', 'SubscriberController@destroy')->name('subscriber.destroy');

    Route::get('/settings', 'SettingsController@index')->name('settings.index');
    Route::put('/setting/profile-update', 'SettingsController@updateProfile')->name('profile.update');
    Route::put('/setting/password-update', 'SettingsController@updatePassword')->name('password.update');

});


//Author Group Route
Route::group(['as'=>'author.','prefix'=>'author','namespace'=>'Author','middleware'=>['auth','author']],function(){
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('post', 'PostController');

    Route::get('/favourite', 'FavouriteController@index')->name('favourite.post');
    Route::delete('/favourite/{post}', 'FavouriteController@destroy')->name('favourite.destroy');

    Route::get('/comment', 'CommentController@index')->name('comment.index');
    Route::delete('/comment/{comment}', 'CommentController@destroy')->name('comment.destroy');

    Route::get('/settings', 'SettingsController@index')->name('settings.index');
    Route::put('/setting/profile-update', 'SettingsController@updateProfile')->name('profile.update');
    Route::put('/setting/password-update', 'SettingsController@updatePassword')->name('password.update');

});

//its working all page of this project if we use * sine;
/* View::composer('*',function($view){
}); */

//its working only delare this page if we identify this
View::composer('layouts.frontend.partials.footer',function($view){
    $categories = App\Category::all();
    $view->with('categories',$categories);
});
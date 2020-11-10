<?php

// Public Blog Routes
Route::get('/Blog', 'DarkBlog\Http\Controllers\BlogController@index')->middleware('web')->name('blog');
Route::get('/Blog/tag/{tag}', 'DarkBlog\Http\Controllers\BlogController@showTag');
Route::get('/Blog/subscribe', 'DarkBlog\Http\Controllers\BlogController@subscribe')->name('blog.subscribe');
Route::post('/Blog/subscribe', 'DarkBlog\Http\Controllers\BlogController@storeSubscription')->name('subscribe');

// Secured Blog Routes
Route::group(['prefix' => '/Blog', 'as' => 'blog.', 'middleware' => ['web', 'auth']], function () {
    Route::post('/', 'DarkBlog\Http\Controllers\BlogController@store');
    Route::get('/admin', 'DarkBlog\Http\Controllers\BlogController@admin')->name('admin');
    Route::get('/create', 'DarkBlog\Http\Controllers\BlogController@create')->name('create');
    Route::get('/drafts', 'DarkBlog\Http\Controllers\BlogController@showDrafts')->name('drafts');
    Route::get('/published', 'DarkBlog\Http\Controllers\BlogController@showPublished')->name('published');
    Route::get('/subscribe/force', 'DarkBlog\Http\Controllers\BlogController@subscribeForce')->name('subscribe.force');
    Route::post('/subscribe/force', 'DarkBlog\Http\Controllers\BlogController@storeSubscriptionForce')->name('subscribe.force.post');
    Route::get('/subscribers', 'DarkBlog\Http\Controllers\BlogController@showSubscribers');
    Route::get('/{post}', 'DarkBlog\Http\Controllers\BlogController@show');
    Route::post('/{post}', 'DarkBlog\Http\Controllers\BlogController@update');
    Route::delete('/{post}', 'DarkBlog\Http\Controllers\BlogController@destroy')->name('delete');
    Route::post('/{post}/publish', 'DarkBlog\Http\Controllers\BlogController@publish')->name('publish');
    Route::post('/{post}/schedule/{schedule}', 'DarkBlog\Http\Controllers\BlogController@schedule')->name('schedule');
    Route::get('/{post}/edit', 'DarkBlog\Http\Controllers\BlogController@edit')->name('edit');
    Route::post('/{post}/tag/{tag}', 'DarkBlog\Http\Controllers\BlogController@addTag');
});

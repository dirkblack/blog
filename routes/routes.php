<?php

// Public Blog Routes
Route::get('/Blog', 'BlogController@index')->middleware('web')->name('blog');
Route::get('/Blog/tag/{tag}', 'BlogController@showTag');
Route::get('/Blog/subscribe', 'BlogController@subscribe')->name('blog.subscribe');
Route::post('/Blog/subscribe', 'BlogController@storeSubscription')->name('subscribe');

// Secured Blog Routes
Route::group(['prefix' => '/Blog', 'as' => 'blog.', 'middleware' => ['auth', 'web']], function () {
    Route::post('/', 'BlogController@store');
    Route::get('/admin', 'BlogController@admin')->name('admin');
    Route::get('/create', 'BlogController@create')->name('create');
    Route::get('/drafts', 'BlogController@showDrafts')->name('drafts');
    Route::get('/published', 'BlogController@showPublished')->name('published');
    Route::get('/subscribe/force', 'BlogController@subscribeForce')->name('subscribe.force');
    Route::post('/subscribe/force', 'BlogController@storeSubscriptionForce')->name('subscribe.force.post');
    Route::get('/subscribers', 'BlogController@showSubscribers');
    Route::get('/{post}', 'BlogController@show');
    Route::post('/{post}', 'BlogController@update');
    Route::delete('/{post}', 'BlogController@destroy')->name('delete');
    Route::post('/{post}/publish', 'BlogController@publish')->name('publish');
    Route::post('/{post}/schedule/{schedule}', 'BlogController@schedule')->name('schedule');
    Route::get('/{post}/edit', 'BlogController@edit')->name('edit');
    Route::post('/{post}/tag/{tag}', 'BlogController@addTag');
});

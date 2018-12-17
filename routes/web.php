<?php

Route::get('/', function () {
    return redirect('posts');
});

Route::resource('posts', 'PostsController', ['except' => ['show']]);
Route::get('posts/{slug}', 'PostsController@show')->name('posts.show');
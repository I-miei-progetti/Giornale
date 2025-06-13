<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Rotte pubbliche
Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'homepage')->name('homepage');
    Route::get('/careers', 'careers')->name('careers');
    Route::post('careers/submit')->name('careers.submit');
});

// Rotte articoli
Route::prefix('article')->controller(ArticleController::class)->group(function () {
    Route::get('/create', 'create')->name('article.create');
    Route::post('/store', 'store')->name('article.store');
    Route::get('/index', 'index')->name('article.index');
    Route::get('/show/{article}', 'show')->name('article.show');
    Route::get('/category/{category}', 'byCategory')->name('article.byCategory');

    Route::get('/user/{user}', 'byUser')->name('article.byUser');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;

// Rotte pubbliche
Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'homepage')->name('homepage');
    Route::get('/careers', 'careers')->name('careers');
     Route::post('/careers/submit', 'careersSubmit')->name('careers.submit');
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

Route::middleware('admin')->group(function(){
  Route::get('/admin/dashboard', [AdminController::class,'dashboard'])->name('admin.dashboard');
  Route::get('/admin/{user}/set-admin', [AdminController::class, 'setAdmin'])->name('admin.setAdmin');
  Route::get('/admin/{user}/set-revisor', [AdminController::class, 'setRevisor'])->name('admin.setRevisor');
  Route::get('/admin/{user}/set-writer', [AdminController::class, 'setWriter'])->name('admin.setWriter');
});
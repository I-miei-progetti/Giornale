<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RevisorController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::patch('/admin/{user}/set-admin', [AdminController::class, 'setAdmin'])->name('admin.setAdmin');
    Route::patch('/admin/{user}/set-revisor', [AdminController::class, 'setRevisor'])->name('admin.setRevisor');
    Route::patch('/admin/{user}/set-writer', [AdminController::class, 'setWriter'])->name('admin.setWriter');
    Route::put('/admin/edit/tag/{tag}', [AdminController::class, 'editTag'])->name('admin.editTag');
    Route::delete('/admin/delete/tag/{tag}', [AdminController::class, 'deleteTag'])->name('admin.deleteTag');
    Route::put('/admin/edit/category/{category}', [AdminController::class, 'editCategory'])->name('admin.editCategory');
    Route::post ('/admin/category/store',[AdminConroller::class,'storeCategory'])->name('admin.storeCategory');

});

Route::middleware('revisor')->group(function () {
    Route::get('/revisor/dashboard', [RevisorController::class, 'dashboard'])->name('revisor.dashboard');
    Route::post('/revisor{article}/accept', [RevisorController::class, 'acceptArticle'])->name('revisor.acceptArticle');
    Route::post('/revisor{article}/reject', [RevisorController::class, 'rejectArticle'])->name('revisor.rejectArticle');
    Route::post('/revisor/{article}/undo', [RevisorController::class, 'undoArticle'])->name('revisor.undoArticle');

});

Route::middleware('writer')->group(function () {
    Route::get('/article/create', [ArticleController::class, 'create'])->name('article.create');
    Route::post('/article/store', [ArticleController::class, 'store'])->name('article.store');
});

Route::get('/article/search', [ArticleController::class, 'articleSearch'])->name('article.search');

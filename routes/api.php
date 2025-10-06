<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Article Routes
Route::middleware(['web','auth:web'])->prefix('articles')->group(function () {

    Route::get('/listing', [ArticleController::class, 'showCollection'])->name('api.articles.listing');  
    Route::get('/show/{id}', [ArticleController::class, 'show'])->name('api.articles.show'); 
    Route::get('/index', [ArticleController::class, 'index'])->name('api.articles.index');  
    Route::post('/store', [ArticleController::class, 'store'])->name('api.articles.store'); 
    Route::patch('/update/{id}', [ArticleController::class, 'update'])->name('api.articles.update'); 
    Route::delete('/delete/{id}', [ArticleController::class, 'destroy'])->name('api.articles.delete');
    
});

//Category Routes
Route::middleware(['web','auth:web'])->prefix('categories')->group(function () {

    Route::get('/index', [CategoryController::class, 'index'])->name('api.categories.index');  
    Route::post('/store', [CategoryController::class, 'store'])->name('api.categories.store'); 
    Route::patch('/update/{id}', [CategoryController::class, 'update'])->name('api.categories.update'); 
    Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('api.categories.delete');
    
});
//Tag Routes
Route::middleware(['web','auth:web'])->prefix('tags')->group(function () {

    Route::get('/index', [TagController::class, 'index'])->name('api.tags.index');  
    Route::post('/store', [TagController::class, 'store'])->name('api.tags.store'); 
    Route::patch('/update/{id}', [TagController::class, 'update'])->name('api.tags.update'); 
    Route::delete('/delete/{id}', [TagController::class, 'destroy'])->name('api.tags.delete');
    
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');



Route::middleware(['auth'])->get('/generate-token', function (Request $request) {
   $request->user()->tokens()->delete();

    $token = $request->user()->createToken('API Token')->plainTextToken;

    return response()->json([
        'user' => $request->user()->only(['id', 'name', 'email']),
        'token' => $token,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    //Article Route
    Route::resource('articles',ArticleController::class);

    //admin route
    // Route::middleware(['role:admin'])->group(function () {
    Route::middleware(['auth:sanctum, ability:create-categories,create-tags'])->group(function () {
        //Category Route
        Route::resource('categories',CategoryController::class);
        //Tag Route
        Route::resource('tags',TagController::class);

    });

});

require __DIR__.'/auth.php';

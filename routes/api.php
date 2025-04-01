<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/**
 * route "/register"
 * @method "POST"
 */
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');

/**
 * route "/login"
 * @method "GET"
 */
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');

/**
 * route "/user"
 * @method "GET"
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * route "/logout"
 * @method "POST"
 */
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

/**
 * route "/products"
 * @method "GET"
 */
Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
Route::post('/products', [ProductController::class, 'index'])->name('product.index');
Route::post('/products', [ProductController::class, 'store'])->name('product.store');
Route::post('/products/{id}', [ProductController::class, 'update'])->name('product.update');
Route::post('/products/{id}/delete', [ProductController::class, 'delete'])->name('product.delete');

/**
 * route "/categories"
 * @method "GET"
 */
Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
/**
 * route "/categories"
 * @method "GET"
 */
Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');

/**
 * route "/tags"
 * @method "GET"
 */
Route::get('/tags', [TagController::class, 'index'])->name('tag.index');
/**
 * route "/tags"
 * @method "GET"
 */
Route::post('/tags', [TagController::class, 'store'])->name('tag.store');
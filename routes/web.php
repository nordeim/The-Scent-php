<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\ScentProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NewsletterController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Moods
Route::get('/moods', [MoodController::class, 'index'])->name('moods.index');
Route::get('/moods/{mood:slug}', [MoodController::class, 'show'])->name('moods.show');

// Scent Profiles
Route::get('/scent-profiles', [ScentProfileController::class, 'index'])->name('scent-profiles.index');
Route::get('/scent-profiles/{profile:slug}', [ScentProfileController::class, 'show'])->name('scent-profiles.show');

// Cart
Route::group(['middleware' => ['web']], function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
});

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
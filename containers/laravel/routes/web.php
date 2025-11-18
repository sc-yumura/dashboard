<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers;

// =======================
// 公開ページ
// =======================
Route::get('/', [Controllers\ProductsController::class, 'index'])->name('home');

// =======================
// 商品
// =======================
Route::get('/products/{product:slug}', [Controllers\ProductsController::class, 'show'])->name('products.show');

// =======================
// カテゴリ
// =======================
// カテゴリと言いつつ表示するのが商品一覧なのでこのpathおかしくねえかとなり保留
// Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
// Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// =======================
// カート
// =======================
Route::get('/cart', [Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [Controllers\CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{item}', [Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [Controllers\CartController::class, 'destroy'])->name('cart.destroy');

// =======================
// チェックアウト
// =======================
Route::get('/checkout', [Controllers\CheckoutController::class, 'index'])->name('checkout.index');

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
require __DIR__.'/store.php';
require __DIR__.'/mypage.php';

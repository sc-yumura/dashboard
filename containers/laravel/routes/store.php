<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store;

Route::middleware(['auth:store'])->prefix('store')->name('store.')->group(function () {
    // プロフィール（事業者情報）
    Route::get('/profile', [Store\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [Store\ProfileController::class, 'update'])->name('profile.update');

    // 商品管理
    Route::get('/products', [Store\ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/create', [Store\ProductsController::class, 'create'])->name('products.create');
    Route::post('/products', [Store\ProductsController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [Store\ProductsController::class, 'edit'])->name('products.edit');
    Route::post('/products/{product}', [Store\ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [Store\ProductsController::class, 'destroy'])->name('products.destroy');

    // バリエーション
    Route::get('/products/{product}/variants', [Store\Products\VariantsController::class, 'index'])->name('variants.index');
    Route::post('/products/{product}/variants', [Store\Products\VariantsController::class, 'store'])->name('variants.store');
    Route::patch('/variants/{variant}', [Store\Products\VariantsController::class, 'update'])->name('variants.update');
    Route::delete('/variants/{variant}', [Store\Products\VariantsController::class, 'destroy'])->name('variants.destroy');

    // 注文
    Route::get('/orders', [Store\OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Store\OrdersController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/ship', [Store\OrdersController::class, 'ship'])->name('orders.ship');
    Route::post('/orders/{order}/cancel', [Store\OrdersController::class, 'cancel'])->name('orders.cancel');
});

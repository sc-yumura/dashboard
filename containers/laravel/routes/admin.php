<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // ユーザー管理
    Route::get('/users', [Admin\UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [Admin\UsersController::class, 'show'])->name('users.show');
    // Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');

    // 出店者管理
    Route::get('/stores', [Admin\StoresController::class, 'index'])->name('stores.index');
    Route::get('/stores/{store}', [Admin\StoresController::class, 'show'])->name('stores.show');
    Route::post('/stores/{store}/suspend', [Admin\StoresController::class, 'suspend'])->name('stores.suspend');

    // 商品管理
    Route::get('/products', [Admin\ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [Admin\ProductsController::class, 'show'])->name('products.show');
    Route::post('/products/{product}/disable', [Admin\ProductsController::class, 'disable'])->name('products.disable');

    // 注文管理
    Route::get('/orders', [Admin\OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Admin\OrdersController::class, 'show'])->name('orders.show');

    // レビュー管理
    Route::get('/reports', [Admin\ReportsController::class, 'index'])->name('reports.index');
    Route::post('/reviews/{review}/remove', [Admin\ReviewsController::class, 'remove'])->name('reviews.remove');
});

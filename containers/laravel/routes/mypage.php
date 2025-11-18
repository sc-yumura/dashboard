<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyPage;

Route::middleware(['auth'])->prefix('mypage')->name('mypage.')->group(function () {
    // プロフィール
    Route::get('/profile', [MyPage\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [MyPage\ProfileController::class, 'update'])->name('profile.update');

    // 配送先
    Route::get('/address', [MyPage\AddressController::class, 'index'])->name('address.index');
    Route::post('/address', [MyPage\AddressController::class, 'store'])->name('address.store');
    Route::get('/address/{address}/edit', [MyPage\AddressController::class, 'edit'])->name('address.edit');
    Route::delete('/address/{address}', [MyPage\AddressController::class, 'destroy'])->name('address.destroy');

    // 注文履歴
    Route::get('/orders', [MyPage\OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [MyPage\OrdersController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [MyPage\OrdersController::class, 'cancel'])->name('orders.cancel');

    // レビュー一覧
    // Route::get('/reviews', [MyPage\ReviewsController::class, 'myReviews'])->name('reviews.index');
});

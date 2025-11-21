<?php

test('未ログインユーザーは商品一覧を表示できる', function () {
    $response = $this->get(route('products.index'));

    $response->assertStatus(200);
});

test('未ログインユーザーは商品詳細を表示できる', function () {
    $product = \App\Models\Product::factory()->create();

    $response = $this->get(route('products.show', $product->id));

    $response->assertStatus(200);
});

// test('未ログインユーザーはカート画面にアクセスできる', function () {
//     $response = $this->get(route('cart.index'));

//     $response->assertStatus(200);
// });

// test('未ログインユーザーは購入画面にアクセスできない', function () {
//     $response = $this->get(route('purchase.index'));

//     $response->assertRedirect(route('login'));
// });

// test('未ログイン状態で購入画面にアクセスしようとするとログイン画面にリダイレクトされる', function () {
//     $response = $this->get(route('purchase.index'));

//     $response->assertRedirect(route('login'));
// });
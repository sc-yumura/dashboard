<?php

test('登録画面が表示できる', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

test('新しいユーザーを登録できる', function () {
    $response = $this->withSession([])->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        '_token' => csrf_token()
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home', absolute: false));
});

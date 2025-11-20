<?php

use App\Models\AuthenticateAccount;
use Inertia\Testing\AssertableInertia as Assert;

test('パスワード確認画面が表示できる', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this->actingAs($account)->get(route('password.confirm'));

    $response->assertStatus(200);

    $response->assertInertia(fn (Assert $page) => $page
        ->component('auth/confirm-password')
    );
});

test('パスワード確認には認証が必要', function () {
    $response = $this->get(route('password.confirm'));

    $response->assertRedirect(route('login'));
});

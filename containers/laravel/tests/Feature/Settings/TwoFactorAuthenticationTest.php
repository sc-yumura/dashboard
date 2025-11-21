<?php

use App\Models\AuthenticateAccount;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

test('二要素認証設定画面が表示されること', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $account = AuthenticateAccount::factory()->withoutTwoFactor()->create();

    $this->actingAs($account)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('two-factor.show'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/two-factor')
            ->where('twoFactorEnabled', false)
        );
});

test('二要素認証設定画面は有効な場合にパスワード確認を要求すること', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    $account = AuthenticateAccount::factory()->create();

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $response = $this->actingAs($account)
        ->get(route('two-factor.show'));

    $response->assertRedirect(route('password.confirm'));
});

test('二要素認証設定画面は無効な場合にパスワード確認を要求しないこと', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    $account = AuthenticateAccount::factory()->create();

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => false,
    ]);

    $this->actingAs($account)
        ->get(route('two-factor.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/two-factor')
        );
});

test('二要素認証設定画面は無効な場合に禁止レスポンスを返すこと', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    config(['fortify.features' => []]);

    $account = AuthenticateAccount::factory()->create();

    $this->actingAs($account)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('two-factor.show'))
        ->assertForbidden();
});
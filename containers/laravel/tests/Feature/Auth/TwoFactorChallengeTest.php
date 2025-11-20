<?php

use App\Models\AuthenticateAccount;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

test('二要素認証チャレンジは未認証時にログインへリダイレクトされる', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    $response = $this->get(route('two-factor.login'));

    $response->assertRedirect(route('login'));
});

test('二要素認証チャレンジ画面が表示できる', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $account = AuthenticateAccount::factory()->create();

    $account->forceFill([
        'two_factor_secret' => encrypt('test-secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    $this->withSession([])->post(route('login'), [
        'email' => $account->email,
        'password' => 'password',
        '_token' => csrf_token(),
    ]);

    $this->get(route('two-factor.login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/two-factor-challenge')
        );
});

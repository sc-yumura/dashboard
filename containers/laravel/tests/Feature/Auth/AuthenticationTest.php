<?php

use App\Models\User;
use App\Models\AuthenticateAccount;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Features;

test('ログイン画面が表示できる', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

test('ユーザーはログイン画面で認証できる', function () {
    $account = AuthenticateAccount::factory()->withoutTwoFactor()->create();

    $response = $this->withSession([])->post(route('login.store'), [
        'email' => $account->email,
        'password' => 'password',
        '_token' => csrf_token(),
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home', absolute: false));
});

test('二要素認証が有効なユーザーは二要素認証チャレンジにリダイレクトされる', function () {
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

    $response = $this->withSession([])->post(route('login'), [
        'email' => $account->email,
        'password' => 'password',
        '_token' => csrf_token(),
    ]);

    $response->assertRedirect(route('two-factor.login'));
    $response->assertSessionHas('login.id', $account->id);
    $this->assertGuest();
});

test('無効なパスワードでは認証できない', function () {
    $account = AuthenticateAccount::factory()->create();

    $this->withSession([])->post(route('login.store'), [
        'email' => $account->email,
        'password' => 'wrong-password',
        '_token' => csrf_token(),
    ]);

    $this->assertGuest();
});

test('ユーザーはログアウトできる', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this->actingAs($account)->withSession([])->post(route('logout'), [
        '_token' => csrf_token(),
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('home'));
});

test('ユーザーはレート制限される', function () {
    $account = AuthenticateAccount::factory()->create();

    RateLimiter::increment(md5('login'.implode('|', [$account->email, '127.0.0.1'])), amount: 5);

    $response = $this->withSession([])->post(route('login.store'), [
        'email' => $account->email,
        'password' => 'wrong-password',
        '_token' => csrf_token(),
    ]);

    $response->assertTooManyRequests();
});

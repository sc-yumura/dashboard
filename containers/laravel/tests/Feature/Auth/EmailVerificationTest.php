<?php

use App\Models\AuthenticateAccount;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

test('メール確認画面を表示できる', function () {
    $account = AuthenticateAccount::factory()->unverified()->create();

    $response = $this->actingAs($account)->get(route('verification.notice'));

    $response->assertStatus(200);
});

test('メールアドレスを確認できる', function () {
    $account = AuthenticateAccount::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $account->id, 'hash' => sha1($account->email)]
    );

    $response = $this->actingAs($account)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($account->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('home', absolute: false).'?verified=1');
});

test('無効なハッシュではメールが確認されない', function () {
    $account = AuthenticateAccount::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $account->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($account)->get($verificationUrl);

    expect($account->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('無効なユーザーIDではメールが確認されない', function () {
    $account = AuthenticateAccount::factory()->create([
        'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => 123, 'hash' => sha1($account->email)]
    );

    $this->actingAs($account)->get($verificationUrl);

    expect($account->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('確認済みユーザーは確認画面からダッシュボードへリダイレクトされる', function () {
    $account = AuthenticateAccount::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($account)->get(route('verification.notice'));

    $response->assertRedirect(route('home', absolute: false));
});

test('既に確認済みのユーザーが確認リンクにアクセスしてもイベントは再発火せずリダイレクトされる', function () {
    $account = AuthenticateAccount::factory()->create([
        'email_verified_at' => now(),
    ]);

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $account->id, 'hash' => sha1($account->email)]
    );

    $this->actingAs($account)->get($verificationUrl)
        ->assertRedirect(route('home', absolute: false).'?verified=1');

    expect($account->fresh()->hasVerifiedEmail())->toBeTrue();
    Event::assertNotDispatched(Verified::class);
});

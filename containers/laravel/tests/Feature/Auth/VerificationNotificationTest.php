<?php

use App\Models\AuthenticateAccount;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

test('sends verification notification', function () {
    Notification::fake();

    $account = AuthenticateAccount::factory()->create([
        'email_verified_at' => null,
    ]);

    $this->actingAs($account)
        ->withSession([])
        ->post(route('verification.send'),[
            '_token' => csrf_token(),
        ])
        ->assertRedirect(route('home'));

    Notification::assertSentTo($account, VerifyEmail::class);
});

test('does not send verification notification if email is verified', function () {
    Notification::fake();

    $account = AuthenticateAccount::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($account)
        ->withSession([])
        ->post(route('verification.send', [
            '_token' => csrf_token(),
        ]))
        ->assertRedirect(route('home', absolute: false));

    Notification::assertNothingSent();
});
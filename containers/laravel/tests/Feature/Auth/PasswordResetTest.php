<?php

use App\Models\AuthenticateAccount;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

test('パスワードリセットリンクの画面を表示できる', function () {
    $response = $this->get(route('password.request'));

    $response->assertStatus(200);
});

test('パスワードリセットリンクをリクエストできる', function () {
    Notification::fake();

    $account = AuthenticateAccount::factory()->create();
    
    $this->withSession([])->post(route('password.email'), [
        'email' => $account->email,
        '_token' => csrf_token(),
    ]);

    Notification::assertSentTo($account, ResetPassword::class);
});

test('パスワードリセット画面を表示できる', function () {
    Notification::fake();

    $account = AuthenticateAccount::factory()->create();

    $this->withSession([])->post(route('password.email'), [
        'email' => $account->email,
        '_token' => csrf_token(),
    ]);

    Notification::assertSentTo($account, ResetPassword::class, function ($notification) {
        $response = $this->get(route('password.reset', $notification->token));

        $response->assertStatus(200);

        return true;
    });
});

test('有効なトークンでパスワードをリセットできる', function () {
    Notification::fake();

    $account = AuthenticateAccount::factory()->create();

    $this->withSession([])->post(route('password.email'), [
        'email' => $account->email,
        '_token' => csrf_token(),
    ]);

    Notification::assertSentTo($account, ResetPassword::class, function ($notification) use ($account) {
        $response = $this->withSession([])->post(route('password.update'), [
            'token' => $notification->token,
            'email' => $account->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            '_token' => csrf_token(),
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        return true;
    });
});

test('無効なトークンではパスワードをリセットできない', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this->withSession([])->post(route('password.update'), [
        'token' => 'invalid-token',
        'email' => $account->email,
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
        '_token' => csrf_token(),
    ]);

    $response->assertSessionHasErrors('email');
});

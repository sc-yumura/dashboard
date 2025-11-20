<?php

use App\Models\AuthenticateAccount;

test('プロフィールページが表示される', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this
        ->actingAs($account)
        ->get(route('profile.edit'));

    $response->assertOk();
});

test('プロフィール情報を更新できる', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this
        ->actingAs($account)
        ->withSession([])
        ->patch(route('profile.update'), [
            'name' => 'Test AuthenticateAccount',
            'email' => 'test@example.com',
            '_token' => csrf_token(),
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    $account->refresh();

    // TODO: Userモデルの更新
    // expect($account->name)->toBe('Test AuthenticateAccount');
    expect($account->email)->toBe('test@example.com');
    expect($account->email_verified_at)->toBeNull();
});

test('メールアドレスが変更されない場合、メール認証の状態は変わらない', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this
        ->actingAs($account)
        ->withSession([])
        ->patch(route('profile.update'), [
            'name' => 'Test AuthenticateAccount',
            'email' => $account->email,
            '_token' => csrf_token(),
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    expect($account->refresh()->email_verified_at)->not->toBeNull();
});

test('ユーザーは自分のアカウントを削除できる', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this
        ->actingAs($account)
        ->withSession([])
        ->delete(route('profile.destroy'), [
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertGuest();
    expect($account->fresh())->toBeNull();
});

test('正しいパスワードを提供しないとアカウントを削除できない', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this
        ->actingAs($account)
        ->from(route('profile.edit'))
        ->withSession([])
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
            '_token' => csrf_token(),
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect(route('profile.edit'));

    expect($account->fresh())->not->toBeNull();
});
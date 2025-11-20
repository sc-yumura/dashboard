<?php

use App\Models\AuthenticateAccount;
use Illuminate\Support\Facades\Hash;

test('パスワード更新ページが表示される', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this
        ->actingAs($account)
        ->get(route('user-password.edit'));

    $response->assertStatus(200);
});

test('パスワードを更新できる', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this
        ->actingAs($account)
        ->from(route('user-password.edit'))->withSession([])
        ->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            '_token' => csrf_token(),
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('user-password.edit'));

    expect(Hash::check('new-password', $account->refresh()->password))->toBeTrue();
});

test('正しいパスワードを提供しないとパスワードを更新できない', function () {
    $account = AuthenticateAccount::factory()->create();

    $response = $this
        ->actingAs($account)
        ->from(route('user-password.edit'))->withSession([])
        ->put(route('user-password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            '_token' => csrf_token(),
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect(route('user-password.edit'));
});
<?php

namespace App\Actions\Fortify;

use App\Models\AuthenticateAccount;
use App\Models\User;
use App\UserStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(AuthenticateAccount::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {

            // 1. User（IDだけのメインエンティティ）を作成
            $user = User::create([
                'name' => $input['name'],
                'status' => UserStatus::Active,
            ]);

            // 2. 認証用レコード AuthenticateAccount を紐づけて作成
            return AuthenticateAccount::create([
                'user_id'  => $user->id,
                'email'    => $input['email'],
                'password' => $input['password'],
            ]);
        });
    }
}

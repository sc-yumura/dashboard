<?php

namespace App\Actions\Fortify;

use App\Models\AuthenticateAccount;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(AuthenticateAccount $account, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $account->forceFill([
            'password' => $input['password'],
        ])->save();
    }
}

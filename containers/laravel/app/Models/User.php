<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'status',
    ];

    public function authenticateAccount(): HasOne
    {
        return $this->hasOne(AuthenticateAccount::class);
    }

    public function deleteAccount(): void
    {
        $this->authenticateAccount()->delete();
        $this->name = "退会済みアカウント";
        $this->status = UserStatus::Canceled;
        $this->save();
        // $this->delete();
    }
}

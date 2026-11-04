<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'google_id', 'avatar_url', 'email_verified_at', 'phone', 'address', 'is_admin', 'promo_opt_in', 'terms_accepted_at', 'profile_completed_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'promo_opt_in' => 'boolean',
            'terms_accepted_at' => 'datetime',
            'profile_completed_at' => 'datetime',
        ];
    }

    /**
     * Apakah pengguna sudah menyelesaikan alur pendaftaran dan menyetujui ketentuan?
     */
    public function hasCompletedRegistration(): bool
    {
        return $this->profile_completed_at !== null;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

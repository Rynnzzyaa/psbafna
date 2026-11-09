<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $referral_code
 * @property string|null $phone
 * @property string|null $bank_info
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'role', 'referral_code', 'phone', 'bank_info', 'password'])]
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
        ];
    }

    /**
     * Get registrations referred by this user.
     *
     * @return HasMany<WifiRegistration, $this>
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(WifiRegistration::class, 'user_id');
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a referrer / pembawa pelanggan.
     */
    public function isReferrer(): bool
    {
        return $this->role === 'referrer';
    }

    /**
     * Generate unique referral code for user.
     */
    public static function generateUniqueReferralCode(string $name): string
    {
        $clean = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $name));
        $prefix = Str::substr($clean, 0, 4) ?: 'WIFI';

        do {
            $code = $prefix.rand(1000, 9999);
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}

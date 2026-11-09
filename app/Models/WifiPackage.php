<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $speed_mbps
 * @property int $price
 * @property string|null $description
 * @property array<string>|null $features
 * @property int $commission_reward
 * @property bool $is_popular
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'slug',
    'speed_mbps',
    'price',
    'description',
    'features',
    'commission_reward',
    'is_popular',
    'is_active',
])]
class WifiPackage extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'speed_mbps' => 'integer',
            'price' => 'integer',
            'commission_reward' => 'integer',
            'features' => 'array',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Registrations for this package.
     *
     * @return HasMany<WifiRegistration, $this>
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(WifiRegistration::class);
    }

    /**
     * Formatted price string.
     */
    public function formattedPrice(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.').'/bln';
    }

    /**
     * Formatted commission reward.
     */
    public function formattedCommission(): string
    {
        return 'Rp '.number_format($this->commission_reward, 0, ',', '.');
    }
}

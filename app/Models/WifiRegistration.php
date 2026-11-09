<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $registration_number
 * @property int|null $user_id
 * @property int $wifi_package_id
 * @property string $customer_name
 * @property string $customer_phone
 * @property string|null $customer_email
 * @property string|null $customer_nik
 * @property string $installation_address
 * @property string $city
 * @property string|null $district
 * @property string|null $postal_code
 * @property string|null $latitude
 * @property string|null $longitude
 * @property Carbon|null $preferred_installation_date
 * @property string|null $customer_notes
 * @property string|null $referral_code_used
 * @property string $status
 * @property bool $terms_agreed
 * @property int $commitment_months
 * @property string|null $admin_notes
 * @property int $commission_amount
 * @property string $commission_status
 * @property Carbon|null $installed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $referrer
 * @property-read WifiPackage|null $wifiPackage
 */
#[Fillable([
    'registration_number',
    'user_id',
    'wifi_package_id',
    'customer_name',
    'customer_phone',
    'customer_email',
    'customer_nik',
    'installation_address',
    'city',
    'district',
    'postal_code',
    'latitude',
    'longitude',
    'preferred_installation_date',
    'customer_notes',
    'referral_code_used',
    'status',
    'terms_agreed',
    'commitment_months',
    'admin_notes',
    'commission_amount',
    'commission_status',
    'installed_at',
])]
class WifiRegistration extends Model
{
    use HasFactory;

    /**
     * Possible registration statuses.
     */
    public const STATUS_PENDING = 'pending';

    public const STATUS_SURVEY = 'survey';

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_INSTALLED = 'installed';

    public const STATUS_REJECTED = 'rejected';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preferred_installation_date' => 'date',
            'installed_at' => 'datetime',
            'commission_amount' => 'integer',
            'terms_agreed' => 'boolean',
            'commitment_months' => 'integer',
        ];
    }

    /**
     * Referrer (pembawa pelanggan) relation.
     *
     * @return BelongsTo<User, $this>
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Wifi package relation.
     *
     * @return BelongsTo<WifiPackage, $this>
     */
    public function wifiPackage(): BelongsTo
    {
        return $this->belongsTo(WifiPackage::class, 'wifi_package_id');
    }

    /**
     * Generate unique registration number.
     */
    public static function generateRegistrationNumber(): string
    {
        $prefix = 'REG-'.date('Ymd');

        do {
            $num = $prefix.'-'.strtoupper(Str::random(5));
        } while (static::where('registration_number', $num)->exists());

        return $num;
    }

    /**
     * Status human-readable label.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu Verifikasi',
            self::STATUS_SURVEY => 'Survey Lokasi ODP',
            self::STATUS_SCHEDULED => 'Jadwal Pasang',
            self::STATUS_INSTALLED => 'Terpasang & Aktif',
            self::STATUS_REJECTED => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    /**
     * Status badge Tailwind color classes.
     */
    public function statusBadgeClasses(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800',
            self::STATUS_SURVEY => 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800',
            self::STATUS_SCHEDULED => 'bg-indigo-100 text-indigo-800 border-indigo-300 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800',
            self::STATUS_INSTALLED => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
            self::STATUS_REJECTED => 'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800',
            default => 'bg-slate-100 text-slate-800 border-slate-300 dark:bg-slate-800 dark:text-slate-300',
        };
    }

    /**
     * Formatted commission string.
     */
    public function formattedCommission(): string
    {
        return 'Rp '.number_format($this->commission_amount, 0, ',', '.');
    }

    /**
     * Check if coordinates are present.
     */
    public function hasCoordinates(): bool
    {
        return ! empty($this->latitude) && ! empty($this->longitude);
    }

    /**
     * Get Google Maps URL for technicians/admin to open location directly.
     */
    public function googleMapsUrl(): ?string
    {
        if (! $this->hasCoordinates()) {
            return null;
        }

        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }

    /**
     * Formatted coordinates string.
     */
    public function coordinatesFormatted(): string
    {
        if (! $this->hasCoordinates()) {
            return 'Belum ditag';
        }

        return "{$this->latitude}, {$this->longitude}";
    }
}

<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\WifiPackage;
use App\Models\WifiRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Pendaftaran Berlangganan WiFi')]
#[Layout('components.layouts.app')]
class CustomerRegistrationForm extends Component
{
    #[Url]
    public ?string $ref = '';

    public ?int $selectedPackageId = null;

    public string $customer_name = '';

    public string $customer_phone = '';

    public string $customer_email = '';

    public string $customer_nik = '';

    public string $installation_address = '';

    public string $city = 'Jakarta Selatan';

    public string $district = '';

    public string $postal_code = '';

    public ?string $latitude = null;

    public ?string $longitude = null;

    public ?string $locationAccuracy = null;

    public bool $terms_agreed = false;

    public ?string $preferred_installation_date = null;

    public string $customer_notes = '';

    public string $referral_code = '';

    public ?string $referrerName = null;

    public bool $isReferralValid = false;

    public ?string $referralCheckMessage = null;

    public bool $submitted = false;

    public ?string $createdRegistrationNumber = null;

    /** @var Collection<int, WifiPackage> */
    public Collection $packages;

    /**
     * @return array<string, array<string>>
     */
    protected function rules(): array
    {
        return [
            'selectedPackageId' => ['required', 'exists:wifi_packages,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_nik' => ['nullable', 'string', 'max:30'],
            'installation_address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'latitude' => ['nullable', 'string', 'max:50'],
            'longitude' => ['nullable', 'string', 'max:50'],
            'preferred_installation_date' => ['nullable', 'date', 'after_or_equal:today'],
            'customer_notes' => ['nullable', 'string', 'max:500'],
            'referral_code' => ['nullable', 'string', 'max:50'],
            'terms_agreed' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'selectedPackageId.required' => 'Silakan pilih salah satu paket WiFi yang Anda inginkan.',
            'customer_name.required' => 'Nama lengkap pelanggan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp/HP wajib diisi agar teknisi dapat menghubungi Anda.',
            'installation_address.required' => 'Alamat lengkap pemasangan wajib diisi.',
            'city.required' => 'Kota/Kabupaten wajib diisi.',
            'district.required' => 'Kecamatan wajib diisi.',
            'preferred_installation_date.after_or_equal' => 'Tanggal rencana pasang minimal hari ini atau setelahnya.',
            'terms_agreed.accepted' => 'Anda wajib menyetujui syarat & ketentuan minimal masa berlangganan selama 18 bulan untuk dapat mengirim formulir pendaftaran.',
        ];
    }

    public function mount(): void
    {
        $this->packages = WifiPackage::where('is_active', true)->orderBy('speed_mbps')->get();

        // Default to popular package if available
        $popular = $this->packages->firstWhere('is_popular', true);
        if ($popular) {
            $this->selectedPackageId = $popular->id;
        } elseif ($this->packages->isNotEmpty()) {
            $this->selectedPackageId = $this->packages->first()->id;
        }

        // Check if referral code is provided in URL
        if (! empty($this->ref)) {
            $this->referral_code = strtoupper(trim($this->ref));
            $this->checkReferralCode();
        }
    }

    public function selectPackage(int $packageId): void
    {
        $this->selectedPackageId = $packageId;
    }

    public function checkReferralCode(): void
    {
        $code = strtoupper(trim($this->referral_code));

        if (empty($code)) {
            $this->referrerName = null;
            $this->isReferralValid = false;
            $this->referralCheckMessage = null;

            return;
        }

        $referrer = User::where('referral_code', $code)->first();

        if ($referrer) {
            $this->referrerName = $referrer->name;
            $this->isReferralValid = true;
            $this->referralCheckMessage = "Kode Referral Valid! Direferensikan oleh: {$referrer->name}";
        } else {
            $this->referrerName = null;
            $this->isReferralValid = false;
            $this->referralCheckMessage = 'Kode referral tidak ditemukan. Pendaftaran tetap dapat dilanjutkan tanpa referral.';
        }
    }

    public function submitRegistration(): void
    {
        $this->validate();

        $referrer = null;
        if (! empty($this->referral_code)) {
            $referrer = User::where('referral_code', strtoupper(trim($this->referral_code)))->first();
        }

        $package = WifiPackage::findOrFail($this->selectedPackageId);

        $registrationNumber = WifiRegistration::generateRegistrationNumber();

        $commissionAmount = $referrer ? $package->commission_reward : 0;

        $registration = WifiRegistration::create([
            'registration_number' => $registrationNumber,
            'user_id' => $referrer?->id,
            'wifi_package_id' => $package->id,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email ?: null,
            'customer_nik' => $this->customer_nik ?: null,
            'installation_address' => $this->installation_address,
            'city' => $this->city,
            'district' => $this->district,
            'postal_code' => $this->postal_code ?: null,
            'latitude' => $this->latitude ?: null,
            'longitude' => $this->longitude ?: null,
            'preferred_installation_date' => $this->preferred_installation_date ?: null,
            'customer_notes' => $this->customer_notes ?: null,
            'referral_code_used' => $referrer ? $referrer->referral_code : null,
            'status' => WifiRegistration::STATUS_PENDING,
            'terms_agreed' => true,
            'commitment_months' => 18,
            'admin_notes' => 'Pendaftaran baru masuk secara online via formulir pelanggan.',
            'commission_amount' => $commissionAmount,
            'commission_status' => $referrer ? 'unpaid' : 'cancelled',
        ]);

        $this->createdRegistrationNumber = $registration->registration_number;
        $this->submitted = true;
    }

    public function resetForm(): void
    {
        $this->reset([
            'customer_name',
            'customer_phone',
            'customer_email',
            'customer_nik',
            'installation_address',
            'district',
            'postal_code',
            'latitude',
            'longitude',
            'locationAccuracy',
            'terms_agreed',
            'preferred_installation_date',
            'customer_notes',
            'submitted',
            'createdRegistrationNumber',
        ]);
    }

    public function render(): View
    {
        return view('livewire.customer-registration-form');
    }
}

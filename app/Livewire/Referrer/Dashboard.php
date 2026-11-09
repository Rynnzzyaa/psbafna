<?php

namespace App\Livewire\Referrer;

use App\Models\User;
use App\Models\WifiPackage;
use App\Models\WifiRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Dashboard Pembawa Pelanggan')]
#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';

    public string $search = '';

    // Direct registration modal state
    public bool $showDirectRegisterModal = false;

    public ?int $modalPackageId = null;

    public string $modalCustomerName = '';

    public string $modalCustomerPhone = '';

    public string $modalInstallationAddress = '';

    public string $modalCity = 'Jakarta Selatan';

    public string $modalDistrict = '';

    public ?string $modalLatitude = null;

    public ?string $modalLongitude = null;

    public bool $modalTermsAgreed = false;

    public ?string $modalPreferredDate = null;

    public string $modalNotes = '';

    public ?string $successMessage = null;

    /** @var Collection<int, WifiPackage> */
    public Collection $packages;

    public function mount(): void
    {
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            // Admin can view or redirect
        }

        $this->packages = WifiPackage::where('is_active', true)->orderBy('speed_mbps')->get();
        if ($this->packages->isNotEmpty()) {
            $this->modalPackageId = $this->packages->first()->id;
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function openDirectRegisterModal(): void
    {
        $this->resetValidation();
        $this->reset([
            'modalCustomerName',
            'modalCustomerPhone',
            'modalInstallationAddress',
            'modalDistrict',
            'modalLatitude',
            'modalLongitude',
            'modalTermsAgreed',
            'modalPreferredDate',
            'modalNotes',
        ]);
        $this->showDirectRegisterModal = true;
    }

    public function closeDirectRegisterModal(): void
    {
        $this->showDirectRegisterModal = false;
    }

    public function submitDirectRegistration(): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        $this->validate([
            'modalPackageId' => ['required', 'exists:wifi_packages,id'],
            'modalCustomerName' => ['required', 'string', 'max:255'],
            'modalCustomerPhone' => ['required', 'string', 'max:20'],
            'modalInstallationAddress' => ['required', 'string', 'max:500'],
            'modalCity' => ['required', 'string', 'max:100'],
            'modalDistrict' => ['required', 'string', 'max:100'],
            'modalLatitude' => ['nullable', 'string', 'max:50'],
            'modalLongitude' => ['nullable', 'string', 'max:50'],
            'modalTermsAgreed' => ['accepted'],
            'modalPreferredDate' => ['nullable', 'date', 'after_or_equal:today'],
            'modalNotes' => ['nullable', 'string', 'max:500'],
        ], [
            'modalPackageId.required' => 'Pilih paket WiFi.',
            'modalCustomerName.required' => 'Nama pelanggan wajib diisi.',
            'modalCustomerPhone.required' => 'Nomor WhatsApp wajib diisi.',
            'modalInstallationAddress.required' => 'Alamat lengkap wajib diisi.',
            'modalDistrict.required' => 'Kecamatan wajib diisi.',
            'modalTermsAgreed.accepted' => 'Pelanggan wajib menyetujui komitmen masa berlangganan minimal 18 bulan.',
        ]);

        $package = WifiPackage::findOrFail($this->modalPackageId);

        $reg = WifiRegistration::create([
            'registration_number' => WifiRegistration::generateRegistrationNumber(),
            'user_id' => $user->id,
            'wifi_package_id' => $package->id,
            'customer_name' => $this->modalCustomerName,
            'customer_phone' => $this->modalCustomerPhone,
            'installation_address' => $this->modalInstallationAddress,
            'city' => $this->modalCity,
            'district' => $this->modalDistrict,
            'latitude' => $this->modalLatitude ?: null,
            'longitude' => $this->modalLongitude ?: null,
            'preferred_installation_date' => $this->modalPreferredDate ?: null,
            'customer_notes' => $this->modalNotes ?: null,
            'referral_code_used' => $user->referral_code,
            'status' => WifiRegistration::STATUS_PENDING,
            'terms_agreed' => true,
            'commitment_months' => 18,
            'admin_notes' => 'Diinput langsung oleh Pembawa Pelanggan: '.$user->name,
            'commission_amount' => $package->commission_reward,
            'commission_status' => 'unpaid',
        ]);

        $this->showDirectRegisterModal = false;
        $this->successMessage = "Pelanggan {$reg->customer_name} berhasil didaftarkan dengan No. Registrasi {$reg->registration_number}!";
    }

    public function render(): View
    {
        /** @var User $user */
        $user = Auth::user();

        // Referrer stats
        $totalBrought = WifiRegistration::where('user_id', $user->id)->count();
        $installedCount = WifiRegistration::where('user_id', $user->id)->where('status', WifiRegistration::STATUS_INSTALLED)->count();
        $inProgressCount = WifiRegistration::where('user_id', $user->id)->whereIn('status', [
            WifiRegistration::STATUS_PENDING,
            WifiRegistration::STATUS_SURVEY,
            WifiRegistration::STATUS_SCHEDULED,
        ])->count();
        $totalCommissionEarned = (int) WifiRegistration::where('user_id', $user->id)->where('status', WifiRegistration::STATUS_INSTALLED)->sum('commission_amount');
        $potentialCommission = (int) WifiRegistration::where('user_id', $user->id)->whereIn('status', [
            WifiRegistration::STATUS_PENDING,
            WifiRegistration::STATUS_SURVEY,
            WifiRegistration::STATUS_SCHEDULED,
        ])->sum('commission_amount');

        // Referrals list query
        $query = WifiRegistration::with(['wifiPackage'])
            ->where('user_id', $user->id)
            ->latest();

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (! empty(trim($this->search))) {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term): void {
                $q->where('customer_name', 'like', $term)
                    ->orWhere('registration_number', 'like', $term)
                    ->orWhere('customer_phone', 'like', $term);
            });
        }

        $referrals = $query->paginate(8);

        $referralUrl = route('register.wifi', ['ref' => $user->referral_code]);

        return view('livewire.referrer.dashboard', [
            'user' => $user,
            'totalBrought' => $totalBrought,
            'installedCount' => $installedCount,
            'inProgressCount' => $inProgressCount,
            'totalCommissionEarned' => $totalCommissionEarned,
            'potentialCommission' => $potentialCommission,
            'referrals' => $referrals,
            'referralUrl' => $referralUrl,
        ]);
    }
}

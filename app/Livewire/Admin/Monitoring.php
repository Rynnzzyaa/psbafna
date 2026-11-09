<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\WifiPackage;
use App\Models\WifiRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Admin Monitoring Pusat - Sistem WiFi & Referral')]
#[Layout('components.layouts.app')]
class Monitoring extends Component
{
    use WithPagination;

    public string $activeTab = 'registrations'; // 'registrations', 'referrers', 'packages'

    // Registration filters
    public string $search = '';

    public string $statusFilter = 'all';

    public string $packageFilter = 'all';

    public string $referrerFilter = 'all';

    // Status Update Modal State
    public bool $showEditModal = false;

    public ?int $editingRegistrationId = null;

    public string $editStatus = 'pending';

    public ?string $editAdminNotes = null;

    public string $editCommissionStatus = 'unpaid';

    public ?string $editPreferredDate = null;

    public ?string $editLatitude = null;

    public ?string $editLongitude = null;

    public ?string $toastMessage = null;

    // Referrer CRUD Modal State
    public bool $showReferrerModal = false;

    public ?int $editingReferrerId = null;

    public string $referrerName = '';

    public string $referrerEmail = '';

    public string $referrerPhone = '';

    public string $referrerCode = '';

    public string $referrerBankInfo = '';

    public string $referrerPassword = '';

    public bool $showDeleteReferrerModal = false;

    public ?int $deletingReferrerId = null;

    public ?string $deletingReferrerName = null;

    // Referrers tab search
    public string $referrerSearch = '';

    /** @var Collection<int, WifiPackage> */
    public Collection $packages;

    /** @var Collection<int, User> */
    public Collection $referrers;

    public function mount(): void
    {
        $this->loadDropdownData();
    }

    public function loadDropdownData(): void
    {
        $this->packages = WifiPackage::orderBy('speed_mbps')->get();
        $this->referrers = User::where('role', 'referrer')->orderBy('name')->get();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPackageFilter(): void
    {
        $this->resetPage();
    }

    public function updatedReferrerFilter(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function openEditModal(int $id): void
    {
        $reg = WifiRegistration::findOrFail($id);
        $this->editingRegistrationId = $reg->id;
        $this->editStatus = $reg->status;
        $this->editAdminNotes = $reg->admin_notes;
        $this->editCommissionStatus = $reg->commission_status;
        $this->editPreferredDate = $reg->preferred_installation_date?->format('Y-m-d');
        $this->editLatitude = $reg->latitude;
        $this->editLongitude = $reg->longitude;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingRegistrationId = null;
    }

    public function saveRegistrationUpdate(): void
    {
        if (! $this->editingRegistrationId) {
            return;
        }

        $reg = WifiRegistration::findOrFail($this->editingRegistrationId);

        $installedAt = $reg->installed_at;
        if ($this->editStatus === WifiRegistration::STATUS_INSTALLED && ! $installedAt) {
            $installedAt = Carbon::now();
        }

        $reg->update([
            'status' => $this->editStatus,
            'admin_notes' => $this->editAdminNotes,
            'commission_status' => $this->editCommissionStatus,
            'preferred_installation_date' => $this->editPreferredDate ?: null,
            'latitude' => $this->editLatitude ?: null,
            'longitude' => $this->editLongitude ?: null,
            'installed_at' => $installedAt,
        ]);

        $this->showEditModal = false;
        $this->toastMessage = "Status registrasi {$reg->registration_number} berhasil diperbarui menjadi {$reg->statusLabel()}!";
    }

    public function deleteRegistration(int $id): void
    {
        $reg = WifiRegistration::findOrFail($id);
        $num = $reg->registration_number;
        $reg->delete();

        $this->toastMessage = "Registrasi {$num} telah dihapus dari sistem.";
    }

    public function togglePackageActive(int $id): void
    {
        $pkg = WifiPackage::findOrFail($id);
        $pkg->is_active = ! $pkg->is_active;
        $pkg->save();

        $this->loadDropdownData();
        $this->toastMessage = "Status paket {$pkg->name} berhasil diperbarui.";
    }

    public function openCreateReferrerModal(): void
    {
        $this->resetReferrerForm();
        $this->showReferrerModal = true;
    }

    public function openEditReferrerModal(int $id): void
    {
        $referrer = User::where('role', 'referrer')->findOrFail($id);

        $this->editingReferrerId = $referrer->id;
        $this->referrerName = $referrer->name;
        $this->referrerEmail = $referrer->email;
        $this->referrerPhone = $referrer->phone ?? '';
        $this->referrerCode = $referrer->referral_code ?? '';
        $this->referrerBankInfo = $referrer->bank_info ?? '';
        $this->referrerPassword = '';

        $this->showReferrerModal = true;
    }

    public function saveReferrer(): void
    {
        $id = $this->editingReferrerId;

        $rules = [
            'referrerName' => ['required', 'string', 'max:255'],
            'referrerEmail' => ['required', 'string', 'email', 'max:255', 'unique:users,email'.($id ? ",{$id}" : '')],
            'referrerPhone' => ['required', 'string', 'max:30', 'unique:users,phone'.($id ? ",{$id}" : '')],
            'referrerCode' => ['nullable', 'string', 'max:30', 'unique:users,referral_code'.($id ? ",{$id}" : '')],
            'referrerBankInfo' => ['nullable', 'string', 'max:255'],
            'referrerPassword' => ['nullable', 'string', 'min:6'],
        ];

        $this->validate($rules, [
            'referrerName.required' => 'Nama lengkap wajib diisi.',
            'referrerEmail.required' => 'Email wajib diisi.',
            'referrerEmail.email' => 'Format email tidak valid.',
            'referrerEmail.unique' => 'Email ini sudah terdaftar oleh pengguna lain.',
            'referrerPhone.required' => 'Nomor HP wajib diisi.',
            'referrerPhone.unique' => 'Nomor HP ini sudah digunakan.',
            'referrerCode.unique' => 'Kode referral ini sudah digunakan.',
            'referrerPassword.min' => 'Password minimal 6 karakter.',
        ]);

        $code = ! empty(trim($this->referrerCode))
            ? strtoupper(trim($this->referrerCode))
            : User::generateUniqueReferralCode($this->referrerName);

        if ($id) {
            $referrer = User::where('role', 'referrer')->findOrFail($id);
            $referrer->name = $this->referrerName;
            $referrer->email = $this->referrerEmail;
            $referrer->phone = $this->referrerPhone;
            $referrer->referral_code = $code;
            $referrer->bank_info = $this->referrerBankInfo;

            if (! empty(trim($this->referrerPassword))) {
                $referrer->password = Hash::make($this->referrerPassword);
            }

            $referrer->save();
            $this->toastMessage = "Akun pembawa pelanggan {$referrer->name} berhasil diperbarui.";
        } else {
            $password = ! empty(trim($this->referrerPassword)) ? $this->referrerPassword : 'password';

            $referrer = User::create([
                'name' => $this->referrerName,
                'email' => $this->referrerEmail,
                'phone' => $this->referrerPhone,
                'referral_code' => $code,
                'bank_info' => $this->referrerBankInfo,
                'role' => 'referrer',
                'password' => Hash::make($password),
            ]);

            $this->toastMessage = "Akun pembawa pelanggan {$referrer->name} (Kode: {$code}) berhasil dibuat.";
        }

        $this->showReferrerModal = false;
        $this->resetReferrerForm();
        $this->loadDropdownData();
    }

    public function confirmDeleteReferrer(int $id): void
    {
        $referrer = User::where('role', 'referrer')->findOrFail($id);
        $this->deletingReferrerId = $referrer->id;
        $this->deletingReferrerName = $referrer->name;
        $this->showDeleteReferrerModal = true;
    }

    public function deleteReferrer(): void
    {
        if (! $this->deletingReferrerId) {
            return;
        }

        $referrer = User::where('role', 'referrer')->findOrFail($this->deletingReferrerId);
        $name = $referrer->name;
        $referrer->delete();

        $this->showDeleteReferrerModal = false;
        $this->deletingReferrerId = null;
        $this->deletingReferrerName = null;
        $this->toastMessage = "Akun pembawa pelanggan {$name} berhasil dihapus.";
        $this->loadDropdownData();
    }

    public function closeReferrerModal(): void
    {
        $this->showReferrerModal = false;
        $this->resetReferrerForm();
    }

    public function resetReferrerForm(): void
    {
        $this->editingReferrerId = null;
        $this->referrerName = '';
        $this->referrerEmail = '';
        $this->referrerPhone = '';
        $this->referrerCode = '';
        $this->referrerBankInfo = '';
        $this->referrerPassword = '';
        $this->resetErrorBag();
    }

    public function render(): View
    {
        // 1. Overall System KPI Metrics
        $totalRegistrations = WifiRegistration::count();
        $pendingCount = WifiRegistration::where('status', WifiRegistration::STATUS_PENDING)->count();
        $surveyCount = WifiRegistration::where('status', WifiRegistration::STATUS_SURVEY)->count();
        $scheduledCount = WifiRegistration::where('status', WifiRegistration::STATUS_SCHEDULED)->count();
        $installedCount = WifiRegistration::where('status', WifiRegistration::STATUS_INSTALLED)->count();
        $rejectedCount = WifiRegistration::where('status', WifiRegistration::STATUS_REJECTED)->count();
        $totalReferrersCount = User::where('role', 'referrer')->count();

        // Estimated active monthly recurring revenue (from installed customers)
        $monthlyRevenue = WifiRegistration::where('status', WifiRegistration::STATUS_INSTALLED)
            ->join('wifi_packages', 'wifi_registrations.wifi_package_id', '=', 'wifi_packages.id')
            ->sum('wifi_packages.price');

        // Total Commission unpaid vs paid
        $totalCommissionPaid = WifiRegistration::where('commission_status', 'paid')->sum('commission_amount');
        $totalCommissionUnpaid = WifiRegistration::where('status', WifiRegistration::STATUS_INSTALLED)
            ->where('commission_status', 'unpaid')
            ->sum('commission_amount');

        // 2. Registrations Query
        $regQuery = WifiRegistration::with(['wifiPackage', 'referrer'])->latest();

        if ($this->statusFilter !== 'all') {
            $regQuery->where('status', $this->statusFilter);
        }

        if ($this->packageFilter !== 'all') {
            $regQuery->where('wifi_package_id', $this->packageFilter);
        }

        if ($this->referrerFilter !== 'all') {
            if ($this->referrerFilter === 'none') {
                $regQuery->whereNull('user_id');
            } else {
                $regQuery->where('user_id', $this->referrerFilter);
            }
        }

        if (! empty(trim($this->search))) {
            $term = '%'.trim($this->search).'%';
            $regQuery->where(function ($q) use ($term): void {
                $q->where('customer_name', 'like', $term)
                    ->orWhere('registration_number', 'like', $term)
                    ->orWhere('customer_phone', 'like', $term)
                    ->orWhere('installation_address', 'like', $term);
            });
        }

        $registrations = $regQuery->paginate(10);

        // 3. Referrers Performance Query
        $refQuery = User::where('role', 'referrer')
            ->withCount(['registrations as total_brought'])
            ->withCount(['registrations as total_installed' => function ($q): void {
                $q->where('status', WifiRegistration::STATUS_INSTALLED);
            }])
            ->withSum('registrations as total_commission', 'commission_amount');

        if (! empty(trim($this->referrerSearch))) {
            $refTerm = '%'.trim($this->referrerSearch).'%';
            $refQuery->where(function ($q) use ($refTerm): void {
                $q->where('name', 'like', $refTerm)
                    ->orWhere('email', 'like', $refTerm)
                    ->orWhere('referral_code', 'like', $refTerm)
                    ->orWhere('phone', 'like', $refTerm);
            });
        }

        $referrersList = $refQuery->orderByDesc('total_brought')->paginate(10, ['*'], 'refPage');

        return view('livewire.admin.monitoring', [
            'totalRegistrations' => $totalRegistrations,
            'pendingCount' => $pendingCount,
            'surveyCount' => $surveyCount,
            'scheduledCount' => $scheduledCount,
            'installedCount' => $installedCount,
            'rejectedCount' => $rejectedCount,
            'totalReferrersCount' => $totalReferrersCount,
            'monthlyRevenue' => $monthlyRevenue,
            'totalCommissionPaid' => $totalCommissionPaid,
            'totalCommissionUnpaid' => $totalCommissionUnpaid,
            'registrations' => $registrations,
            'referrersList' => $referrersList,
        ]);
    }
}

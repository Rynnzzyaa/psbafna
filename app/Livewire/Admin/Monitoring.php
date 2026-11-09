<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\WifiPackage;
use App\Models\WifiRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
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

<?php

namespace App\Livewire;

use App\Models\WifiRegistration;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Lacak Status Pemasangan WiFi')]
#[Layout('components.layouts.app')]
class CustomerStatusTracker extends Component
{
    #[Url]
    public string $q = '';

    public ?WifiRegistration $registration = null;

    public bool $searched = false;

    public function mount(): void
    {
        if (! empty($this->q)) {
            $this->search();
        }
    }

    public function search(): void
    {
        $term = trim($this->q);
        $this->searched = true;

        if (empty($term)) {
            $this->registration = null;

            return;
        }

        $this->registration = WifiRegistration::with(['wifiPackage', 'referrer'])
            ->where('registration_number', 'LIKE', $term)
            ->orWhere('customer_phone', 'LIKE', "%{$term}%")
            ->latest()
            ->first();
    }

    public function render(): View
    {
        return view('livewire.customer-status-tracker');
    }
}

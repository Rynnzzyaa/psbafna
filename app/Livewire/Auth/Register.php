<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Daftar Pembawa Pelanggan')]
#[Layout('components.layouts.app')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $bank_info = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * @return array<string, array<string>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'bank_info' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function register(): void
    {
        $this->validate();

        $referralCode = User::generateUniqueReferralCode($this->name);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'bank_info' => $this->bank_info,
            'role' => 'referrer',
            'referral_code' => $referralCode,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        session()->flash('success', "Selamat bergabung! Kode referral Anda adalah: {$referralCode}");

        $this->redirect(route('referrer.dashboard'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.register');
    }
}

<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Masuk AFNALINK - teman ketika kamu Healing')]
#[Layout('components.layouts.app')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public string $errorMessage = '';

    /**
     * @return array<string, array<string>>
     */
    protected function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'email.required' => 'Email atau Nomor HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ];
    }

    public function login(): void
    {
        $this->errorMessage = '';
        $this->validate();

        $input = trim($this->email);
        $credentials = [];

        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $input, 'password' => $this->password];
        } else {
            $phoneClean = preg_replace('/[^0-9]/', '', $input) ?? $input;
            $userByPhone = User::where('phone', $input)
                ->orWhere('phone', $phoneClean)
                ->first();

            if ($userByPhone) {
                $credentials = ['email' => $userByPhone->email, 'password' => $this->password];
            } else {
                $credentials = ['phone' => $input, 'password' => $this->password];
            }
        }

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            $user = Auth::user();
            if ($user && $user->isAdmin()) {
                $this->redirect(route('admin.monitoring'), navigate: true);

                return;
            }

            $this->redirect(route('referrer.dashboard'), navigate: true);

            return;
        }

        $this->errorMessage = 'Email/Nomor HP atau password yang Anda masukkan salah.';
    }

    public function render(): View
    {
        return view('livewire.auth.login');
    }
}

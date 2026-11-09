<div class="max-w-lg mx-auto py-6 sm:py-10">
    <div class="bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-600 text-white shadow-lg shadow-emerald-500/30 mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Daftar Jadi Pembawa Pelanggan</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Dapatkan link referral unik dan komisi untuk setiap pelanggan wifi yang Anda bawa!</p>
        </div>

        <form wire:submit="register" class="space-y-4">
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Nama Lengkap</label>
                <input wire:model="name" type="text" id="name" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Email</label>
                    <input wire:model="email" type="email" id="email" required placeholder="nama@email.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                    @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Nomor WhatsApp / HP</label>
                    <input wire:model="phone" type="text" id="phone" required placeholder="0812xxxxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                    @error('phone') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="bank_info" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Rekening Bank / E-Wallet (Pencairan Komisi)</label>
                <input wire:model="bank_info" type="text" id="bank_info" placeholder="Contoh: BCA - 1234567890 a.n Budi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                @error('bank_info') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Password</label>
                    <input wire:model="password" type="password" id="password" required placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                    @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Konfirmasi Password</label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" required placeholder="Ulangi password" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" wire:loading.attr="disabled" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-md shadow-emerald-500/25 transition duration-150 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                    <span wire:loading.remove>Daftar & Dapatkan Kode Referral</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mendaftarkan...
                    </span>
                </button>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Sudah punya akun pembawa pelanggan? 
                <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-500 dark:text-emerald-400">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>

<div class="min-h-[85vh] flex flex-col justify-center items-center py-8 px-4 sm:px-6 relative" 
     x-data="{ showPassword: false, showForgot: false }">

    <!-- Subtle Background Glow Effects -->
    <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[500px] h-[400px] bg-indigo-900/15 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Header & Logo Section -->
    <div class="text-center mb-6 relative z-10">
        <div class="inline-flex items-center justify-center mb-3 group">
            <div class="relative w-24 h-24 rounded-full p-1 bg-gradient-to-b from-cyan-400/30 to-blue-600/20 shadow-xl shadow-cyan-500/20">
                <img src="{{ asset('images/afnalink-logo.png') }}" 
                     alt="AFNALINK Logo" 
                     class="w-full h-full object-contain drop-shadow-[0_4px_16px_rgba(34,211,238,0.35)] transition-transform duration-300 group-hover:scale-105">
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-wide">
            Masuk AFNALINK
        </h1>
        <p class="text-slate-400 text-sm mt-1 font-normal tracking-wide">
            teman ketika kamu Healing
        </p>
    </div>

    <!-- Card Container -->
    <div class="w-full max-w-[420px] rounded-3xl bg-[#0f182c]/85 border border-slate-700/60 backdrop-blur-xl p-6 sm:p-8 shadow-2xl shadow-blue-950/80 relative z-10">
        
        @if ($errorMessage)
            <div class="mb-5 p-3.5 rounded-2xl bg-rose-950/60 border border-rose-800/80 text-rose-300 text-xs sm:text-sm flex items-start gap-2.5">
                <svg class="w-5 h-5 shrink-0 text-rose-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>{{ $errorMessage }}</span>
            </div>
        @endif

        <form wire:submit="login" class="space-y-4">
            <!-- Email / Phone Field -->
            <div>
                <label for="email" class="block text-xs font-bold tracking-wider text-slate-400 uppercase mb-2">
                    EMAIL ATAU NO. HP
                </label>
                <input wire:model="email" 
                       type="text" 
                       id="email" 
                       required 
                       autocomplete="username" 
                       placeholder="085878942325" 
                       class="w-full px-4 py-3 rounded-2xl bg-[#17233c] border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/70 focus:border-transparent text-sm transition">
                @error('email') 
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-xs font-bold tracking-wider text-slate-400 uppercase mb-2">
                    PASSWORD
                </label>
                <div class="relative">
                    <input wire:model="password" 
                           :type="showPassword ? 'text' : 'password'" 
                           id="password" 
                           required 
                           autocomplete="current-password" 
                           placeholder="••••••••" 
                           class="w-full px-4 py-3 pr-11 rounded-2xl bg-[#17233c] border border-slate-700/80 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/70 focus:border-transparent text-sm transition">
                    
                    <!-- Eye Toggle Button -->
                    <button type="button" 
                            @click="showPassword = !showPassword" 
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200 transition focus:outline-none p-1"
                            aria-label="Toggle password visibility">
                        <!-- Eye Open -->
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye Slash -->
                        <svg x-show="showPassword" x-cloak class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                @error('password') 
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Forgot Password Link -->
            <div class="flex justify-end pt-0.5">
                <button type="button" 
                        @click="showForgot = true" 
                        class="text-xs text-slate-400 hover:text-slate-200 transition font-medium focus:outline-none">
                    Lupa password?
                </button>
            </div>

            <!-- Submit Button (Golden Yellow) -->
            <button type="submit" 
                    wire:loading.attr="disabled" 
                    class="w-full py-3.5 px-4 rounded-2xl bg-[#f5b82e] hover:bg-[#e8ab25] active:scale-[0.99] text-slate-950 font-bold text-base shadow-lg shadow-amber-500/25 transition duration-150 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 mt-3">
                <span wire:loading.remove>Masuk</span>
                <span wire:loading class="flex items-center gap-2 text-slate-950">
                    <svg class="animate-spin h-5 w-5 text-slate-950" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memproses...</span>
                </span>
            </button>
        </form>

        <!-- Register Link -->
        <div class="mt-6 pt-5 border-t border-slate-700/60 text-center">
            <p class="text-xs text-slate-400">
                Belum menjadi pembawa pelanggan? 
                <a href="{{ route('register') }}" class="font-semibold text-yellow-400 hover:text-yellow-300 transition">
                    Daftar Jadi Mitra
                </a>
            </p>
        </div>

        <!-- Quick Demo Access Section -->
        <div class="mt-5 p-3.5 rounded-2xl bg-[#141e35]/80 border border-slate-700/60">
            <div class="text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2 flex items-center justify-between">
                <span class="flex items-center gap-1.5 text-amber-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Akses Cepat Demo
                </span>
                <span class="text-[10px] text-slate-400 font-normal">Klik untuk langsung login</span>
            </div>
            <div class="grid grid-cols-1 gap-1.5 text-xs">
                <a href="{{ route('demo.login', 'admin') }}" 
                   class="p-2 rounded-xl bg-[#1a2642] hover:bg-[#202f52] border border-slate-700/60 transition text-left flex items-center justify-between group">
                    <div>
                        <span class="font-semibold text-blue-400 block group-hover:text-blue-300">1. Admin Monitoring</span>
                        <span class="text-[10px] text-slate-400">admin@wifinet.id</span>
                    </div>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-300 font-medium">Masuk Admin</span>
                </a>
                <a href="{{ route('demo.login', 'referrer') }}" 
                   class="p-2 rounded-xl bg-[#1a2642] hover:bg-[#202f52] border border-slate-700/60 transition text-left flex items-center justify-between group">
                    <div>
                        <span class="font-semibold text-amber-400 block group-hover:text-amber-300">2. Mitra Pembawa Pelanggan</span>
                        <span class="text-[10px] text-slate-400">No HP: 085878942325 / budi@sales.id</span>
                    </div>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 font-medium">Masuk Mitra</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Lupa Password -->
    <div x-show="showForgot" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         @click.self="showForgot = false">
        <div class="w-full max-w-sm rounded-3xl bg-[#101a30] border border-slate-700 p-6 text-center shadow-2xl">
            <div class="w-12 h-12 rounded-2xl bg-amber-400/20 text-amber-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-1">Bantuan Lupa Password</h3>
            <p class="text-xs text-slate-300 mb-4 leading-relaxed">
                Untuk reset password akun AFNALINK Anda, silakan hubungi Customer Care atau Admin kami melalui WhatsApp dengan menyertakan Nomor HP atau Email terdaftar.
            </p>
            <a href="https://wa.me/6285878942325?text=Halo%20Admin%20AFNALINK,%20saya%20butuh%20bantuan%20reset%20password." 
               target="_blank"
               class="block w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md mb-2 transition">
                Hubungi Admin via WhatsApp
            </a>
            <button type="button" 
                    @click="showForgot = false"
                    class="text-xs text-slate-400 hover:text-white transition">
                Tutup
            </button>
        </div>
    </div>
</div>

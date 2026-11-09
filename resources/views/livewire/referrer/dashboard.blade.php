<div class="space-y-6 sm:space-y-8 py-2">
    <!-- Success Alert -->
    @if ($successMessage)
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm flex items-center justify-between gap-3 shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ $successMessage }}</span>
            </div>
            <button type="button" wire:click="$set('successMessage', null)" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 cursor-pointer">✕</button>
        </div>
    @endif

    <!-- Referrer Header & Action Card -->
    <div class="rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-700 to-slate-900 text-white p-6 sm:p-8 shadow-xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-indigo-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Portal Mitra Pembawa Pelanggan
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Halo, {{ $user->name }}!</h1>
                <p class="text-indigo-100 text-xs sm:text-sm max-w-xl">
                    Bagikan tautan atau kode referral Anda kepada tetangga, teman, atau keluarga yang membutuhkan pasang internet WiFi baru. Dapatkan komisi untuk setiap pelanggan yang sukses terpasang!
                </p>
            </div>

            <!-- Fast Action: Direct Input Button -->
            <div class="shrink-0">
                <button 
                    type="button" 
                    wire:click="openDirectRegisterModal" 
                    class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm shadow-lg shadow-emerald-500/30 transition flex items-center justify-center gap-2 cursor-pointer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Input Calon Pelanggan Baru</span>
                </button>
            </div>
        </div>

        <!-- Referral Link & Code Share Panel -->
        <div class="relative z-10 mt-6 pt-6 border-t border-white/15 grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Code Box -->
            <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-between gap-3">
                <div>
                    <span class="text-[11px] uppercase tracking-wider text-indigo-200 block font-semibold">Kode Referral Anda</span>
                    <span class="font-mono text-2xl font-black text-white tracking-wider">{{ $user->referral_code }}</span>
                </div>
                <button 
                    type="button" 
                    onclick="navigator.clipboard.writeText('{{ $user->referral_code }}'); alert('Kode referral {{ $user->referral_code }} berhasil disalin!');" 
                    class="px-3.5 py-2 rounded-xl bg-white text-indigo-900 hover:bg-indigo-50 font-bold text-xs shadow transition cursor-pointer"
                >
                    Salin Kode
                </button>
            </div>

            <!-- Link Box -->
            <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="overflow-hidden w-full">
                    <span class="text-[11px] uppercase tracking-wider text-indigo-200 block font-semibold">Tautan Pendaftaran Pelanggan</span>
                    <span class="font-mono text-xs text-indigo-100 truncate block">{{ $referralUrl }}</span>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                    <button 
                        type="button" 
                        onclick="navigator.clipboard.writeText('{{ $referralUrl }}'); alert('Tautan referral berhasil disalin!');" 
                        class="flex-1 sm:flex-none px-3.5 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-white font-semibold text-xs transition cursor-pointer"
                    >
                        Salin Link
                    </button>
                    @php
                        $waText = urlencode("Halo! Mau pasang internet WiFi murah & cepat tanpa batasan kuota? Yuk daftar langsung lewat rekomendasi saya di link ini: {$referralUrl}");
                    @endphp
                    <a 
                        href="https://api.whatsapp.com/send?text={{ $waText }}" 
                        target="_blank" 
                        class="flex-1 sm:flex-none px-3.5 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-xs flex items-center justify-center gap-1.5 transition shadow"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.203c.043.072.043.419-.101.824z"/>
                        </svg>
                        <span>Share WA</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Referrer Metrics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Total Pelanggan Dibawa</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $totalBrought }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Semua pendaftaran lewat referral Anda</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Terpasang & Aktif</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $installedCount }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Pelanggan aktif menghasilkan komisi</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Sedang Diproses</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $inProgressCount }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Tahap verifikasi, survey & instalasi</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Komisi Berhasil</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">
                Rp {{ number_format($totalCommissionEarned, 0, ',', '.') }}
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">Potensi tertunda: Rp {{ number_format($potentialCommission, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Referrals Table Section -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Pelanggan yang Anda Bawa</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Pantau proses instalasi pelanggan secara real-time dari registrasi hingga aktif</p>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Cari nama / no reg..." 
                    class="px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-48"
                >
                <select 
                    wire:model.live="statusFilter" 
                    class="px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="all">Semua Status</option>
                    <option value="pending">Menunggu Verifikasi</option>
                    <option value="survey">Survey Lokasi</option>
                    <option value="scheduled">Jadwal Pasang</option>
                    <option value="installed">Terpasang & Aktif</option>
                    <option value="rejected">Dibatalkan</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="text-[11px] uppercase tracking-wider text-slate-500 bg-slate-50 dark:bg-slate-800/60 dark:text-slate-400 rounded-xl">
                    <tr>
                        <th class="py-3 px-4 rounded-l-xl">No. Registrasi / Tgl</th>
                        <th class="py-3 px-4">Nama Pelanggan</th>
                        <th class="py-3 px-4">Paket WiFi</th>
                        <th class="py-3 px-4">Alamat & Titik Lokasi</th>
                        <th class="py-3 px-4">Status Pemasangan</th>
                        <th class="py-3 px-4 rounded-r-xl">Komisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($referrals as $item)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition">
                            <td class="py-3.5 px-4 font-medium">
                                <span class="font-mono font-bold text-slate-900 dark:text-white block">{{ $item->registration_number }}</span>
                                <span class="text-[10px] text-slate-400">{{ $item->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 dark:text-white block">{{ $item->customer_name }}</span>
                                <span class="text-[11px] text-slate-500 font-mono">{{ $item->customer_phone }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-blue-600 dark:text-blue-400 block">{{ $item->wifiPackage?->name }}</span>
                                <span class="text-[10px] text-slate-400">{{ $item->wifiPackage?->speed_mbps }} Mbps • {{ $item->wifiPackage?->formattedPrice() }}</span>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $item->district }}, {{ $item->city }}</span>
                                <span class="text-[10px] text-slate-400 block truncate" title="{{ $item->installation_address }}">{{ $item->installation_address }}</span>
                                @if ($item->hasCoordinates())
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <a href="{{ $item->googleMapsUrl() }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 text-[10px] font-semibold hover:bg-blue-100 transition">
                                            <span>📍 Buka di Google Maps</span>
                                            <span>↗</span>
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $item->statusBadgeClasses() }}">
                                    {{ $item->statusLabel() }}
                                </span>
                                @if ($item->admin_notes)
                                    <span class="block text-[10px] text-slate-400 italic mt-0.5 max-w-[200px] truncate" title="{{ $item->admin_notes }}">
                                        Info: {{ $item->admin_notes }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-semibold">
                                <span class="text-indigo-600 dark:text-indigo-400 font-bold block">{{ $item->formattedCommission() }}</span>
                                <span class="text-[10px] {{ $item->commission_status === 'paid' ? 'text-emerald-600 font-semibold' : 'text-slate-400' }}">
                                    {{ $item->commission_status === 'paid' ? '✓ Sudah Cair' : ($item->status === 'installed' ? 'Siap Dicairkan' : 'Menunggu Pemasangan') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Belum ada pelanggan yang terdaftar dengan kriteria pencarian ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $referrals->links() }}
        </div>
    </div>

    <!-- Direct Input Modal -->
    @if ($showDirectRegisterModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Input Pendaftaran Pelanggan Baru</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Otomatis terhubung dengan kode referral Anda: <strong class="font-mono text-indigo-600">{{ $user->referral_code }}</strong></p>
                    </div>
                    <button type="button" wire:click="closeDirectRegisterModal" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">✕</button>
                </div>

                <form wire:submit="submitDirectRegistration" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Pilih Paket WiFi</label>
                        <select wire:model="modalPackageId" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @foreach ($packages as $pkg)
                                <option value="{{ $pkg->id }}">{{ $pkg->name }} ({{ $pkg->speed_mbps }} Mbps) - {{ $pkg->formattedPrice() }} [Bonus: {{ $pkg->formattedCommission() }}]</option>
                            @endforeach
                        </select>
                        @error('modalPackageId') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Nama Lengkap Pelanggan *</label>
                            <input wire:model="modalCustomerName" type="text" placeholder="Nama calon pelanggan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @error('modalCustomerName') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">No. WhatsApp Pelanggan *</label>
                            <input wire:model="modalCustomerPhone" type="text" placeholder="0812xxxxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @error('modalCustomerPhone') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Alamat Lengkap Pemasangan *</label>
                        <textarea wire:model="modalInstallationAddress" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, patokan rumah" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                        @error('modalInstallationAddress') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Kota / Kabupaten *</label>
                            <input wire:model="modalCity" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Kecamatan *</label>
                            <input wire:model="modalDistrict" type="text" placeholder="Contoh: Tebet" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @error('modalDistrict') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- GPS Auto & Manual Coordinates for Mitra Sales -->
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">📍 Titik Koordinat GPS / Lokasi Rumah</label>
                            <button 
                                type="button" 
                                onclick="
                                    if (!navigator.geolocation) { alert('Perangkat Anda tidak mendukung fitur GPS.'); return; }
                                    const b = this;
                                    b.innerText = 'Mendeteksi GPS...';
                                    navigator.geolocation.getCurrentPosition(
                                        p => {
                                            b.innerText = '✓ GPS Terkunci';
                                            @this.set('modalLatitude', p.coords.latitude.toFixed(6));
                                            @this.set('modalLongitude', p.coords.longitude.toFixed(6));
                                        },
                                        e => {
                                            b.innerText = 'Gagal Deteksi GPS';
                                            alert('Gagal mengambil GPS: ' + e.message);
                                        },
                                        { enableHighAccuracy: true, timeout: 10000 }
                                    );
                                "
                                class="px-2.5 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-[11px] shadow transition cursor-pointer"
                            >
                                📍 Ambil Lokasi GPS Saat Ini
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <input wire:model="modalLatitude" type="text" placeholder="Latitude (-6.xxx)" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-mono">
                            <input wire:model="modalLongitude" type="text" placeholder="Longitude (106.xxx)" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Rencana Tgl Pasang</label>
                            <input wire:model="modalPreferredDate" type="date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Catatan Khusus</label>
                            <input wire:model="modalNotes" type="text" placeholder="Catatan untuk teknisi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    <!-- Komitmen 18 Bulan -->
                    <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 space-y-1.5">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input wire:model.live="modalTermsAgreed" type="checkbox" class="mt-0.5 w-4 h-4 rounded border-amber-400 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            <span class="text-xs text-amber-900 dark:text-amber-200 leading-relaxed">
                                Calon pelanggan telah menyetujui <strong>komitmen minimal masa berlangganan 18 bulan</strong> dan ketentuan instalasi fiber optic.
                            </span>
                        </label>
                        @error('modalTermsAgreed') <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                        <button type="button" wire:click="closeDirectRegisterModal" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 cursor-pointer">
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled" 
                            @if (!$modalTermsAgreed) disabled @endif
                            class="px-6 py-2.5 rounded-xl text-white font-semibold text-xs shadow-md transition flex items-center gap-2 {{ $modalTermsAgreed ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/25 cursor-pointer' : 'bg-slate-300 text-slate-500 cursor-not-allowed opacity-60' }}"
                        >
                            <span wire:loading.remove>Simpan & Daftarkan</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

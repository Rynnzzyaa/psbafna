<div class="max-w-3xl mx-auto py-6 sm:py-10 space-y-8">
    <div class="text-center space-y-3">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Lacak Status Pemasangan WiFi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Masukkan Nomor Registrasi (contoh: REG-20261101-A101) atau Nomor WhatsApp yang didaftarkan</p>
    </div>

    <!-- Search Box -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-sm">
        <form wire:submit="search" class="flex gap-2">
            <div class="relative flex-1">
                <input 
                    wire:model="q" 
                    type="text" 
                    placeholder="Nomor Registrasi atau Nomor WhatsApp..." 
                    class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                >
            </div>
            <button 
                type="submit" 
                class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md shadow-blue-500/25 transition cursor-pointer"
            >
                Cari
            </button>
        </form>
    </div>

    <!-- Search Results -->
    @if ($searched)
        @if ($registration)
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-md space-y-6">
                <!-- Header Status -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-5">
                    <div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Registrasi</div>
                        <div class="font-mono text-xl font-bold text-slate-900 dark:text-white">{{ $registration->registration_number }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">Didaftarkan pada: {{ $registration->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold border {{ $registration->statusBadgeClasses() }}">
                            {{ $registration->statusLabel() }}
                        </span>
                    </div>
                </div>

                <!-- Progress Step Tracker -->
                @php
                    $steps = [
                        'pending' => '1. Verifikasi Data',
                        'survey' => '2. Survey ODP & Tiang',
                        'scheduled' => '3. Jadwal Pemasangan',
                        'installed' => '4. Terpasang & Aktif',
                    ];
                    $statusOrder = ['pending' => 1, 'survey' => 2, 'scheduled' => 3, 'installed' => 4, 'rejected' => 0];
                    $currentRank = $statusOrder[$registration->status] ?? 1;
                @endphp

                @if ($registration->status !== 'rejected')
                    <div class="py-4">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach ($steps as $key => $title)
                                @php
                                    $rank = $statusOrder[$key];
                                    $isPassed = $currentRank >= $rank;
                                    $isCurrent = $registration->status === $key;
                                @endphp
                                <div class="p-3 rounded-xl border text-center {{ $isCurrent ? 'bg-blue-50 border-blue-400 dark:bg-blue-950/40 dark:border-blue-700' : ($isPassed ? 'bg-emerald-50/60 border-emerald-300 dark:bg-emerald-950/20 dark:border-emerald-800' : 'bg-slate-50 border-slate-200 opacity-60 dark:bg-slate-800/40 dark:border-slate-800') }}">
                                    <div class="w-6 h-6 mx-auto rounded-full text-xs font-bold flex items-center justify-center mb-1.5 {{ $isCurrent ? 'bg-blue-600 text-white' : ($isPassed ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-400') }}">
                                        @if ($isPassed && !$isCurrent)
                                            ✓
                                        @else
                                            {{ $rank }}
                                        @endif
                                    </div>
                                    <div class="text-[11px] font-semibold {{ $isCurrent ? 'text-blue-700 dark:text-blue-300' : ($isPassed ? 'text-emerald-700 dark:text-emerald-300' : 'text-slate-500') }}">
                                        {{ $title }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm">
                        <span class="font-bold">Permohonan Dibatalkan:</span> Alamat pemasangan saat ini berada di luar jangkauan port tiang ODP fiber optic kami.
                    </div>
                @endif

                <!-- Detail Pendaftaran -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-4 border-t border-slate-200 dark:border-slate-800">
                    <div class="space-y-2">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 block font-medium">Nama Pelanggan</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $registration->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 block font-medium">Paket WiFi Terpilih</span>
                            <span class="font-bold text-blue-600 dark:text-blue-400 text-sm">{{ $registration->wifiPackage?->name }} ({{ $registration->wifiPackage?->speed_mbps }} Mbps)</span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 block font-medium">Alamat Pemasangan</span>
                            <span class="text-slate-700 dark:text-slate-300">{{ $registration->installation_address }}, {{ $registration->district }}, {{ $registration->city }}</span>
                            @if ($registration->hasCoordinates())
                                <div class="mt-1">
                                    <a href="{{ $registration->googleMapsUrl() }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                                        <span>📍 Titik Lokasi: {{ $registration->coordinatesFormatted() }} (Buka di Maps)</span>
                                        <span>↗</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2">
                        @if ($registration->referrer)
                            <div>
                                <span class="text-slate-500 dark:text-slate-400 block font-medium">Pembawa Pelanggan (Referral)</span>
                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $registration->referrer->name }} ({{ $registration->referral_code_used }})</span>
                            </div>
                        @endif

                        @if ($registration->admin_notes)
                            <div class="p-3 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 block mb-1">Catatan Tim Lapangan / Admin:</span>
                                <p class="text-slate-700 dark:text-slate-300 italic">{{ $registration->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="p-8 text-center rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3">
                <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Data Pendaftaran Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Pastikan nomor registrasi atau nomor WhatsApp yang Anda masukkan sudah benar dan sesuai saat pendaftaran.
                </p>
            </div>
        @endif
    @endif
</div>

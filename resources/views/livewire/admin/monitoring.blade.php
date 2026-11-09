<div class="space-y-6 sm:space-y-8 py-2">
    <!-- Toast Notification -->
    @if ($toastMessage)
        <div class="p-4 rounded-2xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 text-sm flex items-center justify-between gap-3 shadow-md">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ $toastMessage }}</span>
            </div>
            <button type="button" wire:click="$set('toastMessage', null)" class="text-xs font-semibold text-blue-700 hover:text-blue-900 cursor-pointer">✕</button>
        </div>
    @endif

    <!-- Admin Header Banner -->
    <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 text-white p-6 sm:p-8 shadow-xl relative overflow-hidden border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 backdrop-blur-md border border-blue-400/30 text-xs font-semibold text-blue-300">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping"></span>
                    Admin Control & Monitoring Center
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Monitoring Terpadu Sistem WiFi & Referral</h1>
                <p class="text-slate-300 text-xs sm:text-sm max-w-2xl">
                    Pemegang kendali penuh seluruh pendaftaran pelanggan WiFi, pemantauan progress instalasi teknisi, validasi komisi, dan tracking performa pembawa pelanggan (mitra sales).
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('register.wifi') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-xs font-semibold text-white transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span>Form Pendaftaran Publik</span>
                </a>
            </div>
        </div>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Total Pendaftar</span>
            <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $totalRegistrations }}</div>
            <span class="text-[10px] text-slate-400">Semua pendaftar</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600 block">Menunggu</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $pendingCount }}</div>
            <span class="text-[10px] text-slate-400">Verifikasi baru</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 block">Survey & Jadwal</span>
            <div class="text-2xl font-black text-blue-600 mt-1">{{ $surveyCount + $scheduledCount }}</div>
            <span class="text-[10px] text-slate-400">{{ $surveyCount }} survey, {{ $scheduledCount }} pasang</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 block">Aktif Terpasang</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $installedCount }}</div>
            <span class="text-[10px] text-slate-400">Pelanggan online</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 block">Pembawa Pelanggan</span>
            <div class="text-2xl font-black text-indigo-600 mt-1">{{ $totalReferrersCount }}</div>
            <span class="text-[10px] text-slate-400">Mitra aktif</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Omset Berlangganan</span>
            <div class="text-base sm:text-lg font-black text-slate-900 dark:text-white mt-1">
                Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-slate-400">/ bulan (aktif)</span>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-2">
        <button 
            type="button" 
            wire:click="setTab('registrations')" 
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer {{ $activeTab === 'registrations' ? 'bg-blue-600 text-white shadow' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span>Semua Pendaftaran Pelanggan ({{ $totalRegistrations }})</span>
        </button>

        <button 
            type="button" 
            wire:click="setTab('referrers')" 
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer {{ $activeTab === 'referrers' ? 'bg-indigo-600 text-white shadow' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Monitoring Pembawa Pelanggan ({{ $totalReferrersCount }})</span>
        </button>

        <button 
            type="button" 
            wire:click="setTab('packages')" 
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer {{ $activeTab === 'packages' ? 'bg-slate-800 text-white shadow dark:bg-slate-700' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <span>Paket WiFi ({{ $packages->count() }})</span>
        </button>
    </div>

    <!-- TAB 1: REGISTRATIONS -->
    @if ($activeTab === 'registrations')
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-5">
            <!-- Filter Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Pencarian</label>
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        placeholder="Nama / No Reg / HP / Alamat..." 
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Status Pemasangan</label>
                    <select 
                        wire:model.live="statusFilter" 
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="all">Semua Status</option>
                        <option value="pending">Menunggu Verifikasi ({{ $pendingCount }})</option>
                        <option value="survey">Survey Lokasi ({{ $surveyCount }})</option>
                        <option value="scheduled">Jadwal Pasang ({{ $scheduledCount }})</option>
                        <option value="installed">Terpasang & Aktif ({{ $installedCount }})</option>
                        <option value="rejected">Dibatalkan ({{ $rejectedCount }})</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Paket WiFi</label>
                    <select 
                        wire:model.live="packageFilter" 
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="all">Semua Paket</option>
                        @foreach ($packages as $pkg)
                            <option value="{{ $pkg->id }}">{{ $pkg->name }} ({{ $pkg->speed_mbps }} Mbps)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Pembawa Pelanggan</label>
                    <select 
                        wire:model.live="referrerFilter" 
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="all">Semua Sumber</option>
                        <option value="none">Tanpa Referral (Organik)</option>
                        @foreach ($referrers as $ref)
                            <option value="{{ $ref->id }}">{{ $ref->name }} ({{ $ref->referral_code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Registrations Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="text-[11px] uppercase tracking-wider text-slate-500 bg-slate-50 dark:bg-slate-800/60 dark:text-slate-400 rounded-xl">
                        <tr>
                            <th class="py-3 px-4 rounded-l-xl">No. Reg / Tgl</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Paket & Lokasi</th>
                            <th class="py-3 px-4">Pembawa Pelanggan</th>
                            <th class="py-3 px-4">Status & Catatan</th>
                            <th class="py-3 px-4 rounded-r-xl text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($registrations as $item)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition">
                                <td class="py-3.5 px-4 font-medium">
                                    <span class="font-mono font-bold text-slate-900 dark:text-white block">{{ $item->registration_number }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-900 dark:text-white block">{{ $item->customer_name }}</span>
                                    <span class="text-[11px] text-slate-500 font-mono">{{ $item->customer_phone }}</span>
                                    @if ($item->customer_email)
                                        <span class="text-[10px] text-slate-400 block">{{ $item->customer_email }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-blue-600 dark:text-blue-400 block">{{ $item->wifiPackage?->name }} ({{ $item->wifiPackage?->speed_mbps }} Mbps)</span>
                                    <span class="text-[11px] text-slate-700 dark:text-slate-300">{{ $item->district }}, {{ $item->city }}</span>
                                    <span class="text-[10px] text-slate-400 block truncate max-w-xs" title="{{ $item->installation_address }}">{{ $item->installation_address }}</span>
                                    @if ($item->hasCoordinates())
                                        <div class="mt-1 flex items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1 text-[10px] font-mono text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 px-1.5 py-0.5 rounded border border-emerald-200 dark:border-emerald-800">
                                                📍 {{ $item->coordinatesFormatted() }}
                                            </span>
                                            <a href="{{ $item->googleMapsUrl() }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-[10px] font-bold hover:underline" title="Buka di Google Maps">
                                                Maps ↗
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($item->referrer)
                                        <span class="font-bold text-indigo-600 dark:text-indigo-400 block">{{ $item->referrer->name }}</span>
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                            {{ $item->referral_code_used }}
                                        </span>
                                        <span class="text-[10px] text-slate-500 block mt-0.5 font-semibold">Komisi: {{ $item->formattedCommission() }} ({{ $item->commission_status }})</span>
                                    @else
                                        <span class="text-slate-400 italic">Pendaftaran Organik</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $item->statusBadgeClasses() }}">
                                        {{ $item->statusLabel() }}
                                    </span>
                                    @if ($item->admin_notes)
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 italic mt-1 max-w-[200px] truncate" title="{{ $item->admin_notes }}">
                                            Catatan: {{ $item->admin_notes }}
                                        </p>
                                    @endif
                                    @if ($item->preferred_installation_date)
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Tgl Pasang: {{ $item->preferred_installation_date->format('d/m/Y') }}</span>
                                    @endif
                                    <span class="inline-block mt-1 text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 font-semibold border border-slate-200 dark:border-slate-700">
                                        Kontrak: {{ $item->commitment_months ?? 18 }} Bulan {{ $item->terms_agreed ? '✓' : '' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button 
                                            type="button" 
                                            wire:click="openEditModal({{ $item->id }})" 
                                            class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300 font-semibold text-[11px] transition cursor-pointer"
                                        >
                                            Update Status
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="deleteRegistration({{ $item->id }})" 
                                            wire:confirm="Yakin ingin menghapus registrasi ini dari database?" 
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    Tidak ada data pendaftaran yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-3">
                {{ $registrations->links() }}
            </div>
        </div>
    @endif

    <!-- TAB 2: MONITORING REFERRERS (PEMBAWA PELANGGAN) -->
    @if ($activeTab === 'referrers')
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Leaderboard & Performa Pembawa Pelanggan (Mitra)</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pantau jumlah pelanggan yang dibawa oleh masing-masing agen, konversi pemasangan, dan hak komisi</p>
                </div>

                <input 
                    wire:model.live.debounce.300ms="referrerSearch" 
                    type="text" 
                    placeholder="Cari nama / kode referral..." 
                    class="px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-64"
                >
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="text-[11px] uppercase tracking-wider text-slate-500 bg-slate-50 dark:bg-slate-800/60 dark:text-slate-400 rounded-xl">
                        <tr>
                            <th class="py-3 px-4 rounded-l-xl">Nama Mitra</th>
                            <th class="py-3 px-4">Kode Referral</th>
                            <th class="py-3 px-4">Kontak / Bank</th>
                            <th class="py-3 px-4 text-center">Total Pelanggan Dibawa</th>
                            <th class="py-3 px-4 text-center">Sukses Terpasang</th>
                            <th class="py-3 px-4 text-center">Konversi (%)</th>
                            <th class="py-3 px-4 rounded-r-xl">Total Komisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($referrersList as $ref)
                            @php
                                $conversionRate = $ref->total_brought > 0 ? round(($ref->total_installed / $ref->total_brought) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300 flex items-center justify-center font-bold text-xs">
                                            {{ $ref->initials() }}
                                        </div>
                                        <div>
                                            <span>{{ $ref->name }}</span>
                                            <span class="text-[10px] text-slate-400 block font-normal">{{ $ref->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950 border border-indigo-200 dark:border-indigo-800">
                                        {{ $ref->referral_code }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-slate-700 dark:text-slate-300 block">{{ $ref->phone ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $ref->bank_info ?? 'Belum ada rekening' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center font-extrabold text-base text-slate-900 dark:text-white">
                                    {{ $ref->total_brought }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-extrabold text-base text-emerald-600 dark:text-emerald-400">
                                    {{ $ref->total_installed }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $conversionRate >= 50 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $conversionRate }}%
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-indigo-600 dark:text-indigo-400 text-sm">
                                    Rp {{ number_format($ref->total_commission ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    Tidak ada data pembawa pelanggan yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-3">
                {{ $referrersList->links() }}
            </div>
        </div>
    @endif

    <!-- TAB 3: PACKAGES -->
    @if ($activeTab === 'packages')
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-5">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Paket Internet WiFi & Komisi Referral</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Kelola paket yang tampil di formulir pendaftaran pelanggan dan nominal bonus komisi bagi mitra pembawa pelanggan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($packages as $pkg)
                    <div class="border rounded-2xl p-5 space-y-4 {{ $pkg->is_active ? 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800/40' : 'border-slate-200 dark:border-slate-800 bg-slate-100/50 opacity-60' }}">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-900 dark:text-white text-base">{{ $pkg->name }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pkg->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                {{ $pkg->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        <div>
                            <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $pkg->speed_mbps }} <span class="text-xs font-normal text-slate-400">Mbps</span></div>
                            <div class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $pkg->formattedPrice() }}</div>
                        </div>

                        <div class="p-3 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900">
                            <span class="text-[10px] uppercase font-bold text-indigo-700 dark:text-indigo-300 block">Komisi Pembawa Pelanggan:</span>
                            <span class="text-sm font-extrabold text-indigo-600 dark:text-indigo-400">{{ $pkg->formattedCommission() }}</span>
                        </div>

                        <button 
                            type="button" 
                            wire:click="togglePackageActive({{ $pkg->id }})" 
                            class="w-full py-2 rounded-xl text-xs font-semibold border transition cursor-pointer {{ $pkg->is_active ? 'border-rose-200 text-rose-600 hover:bg-rose-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}"
                        >
                            {{ $pkg->is_active ? 'Nonaktifkan Paket' : 'Aktifkan Paket' }}
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Edit Status Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Update Status Pendaftaran & Monitoring</h3>
                        <p class="text-xs text-slate-500">Ubah progress pemasangan wifi dan status komisi referral</p>
                    </div>
                    <button type="button" wire:click="closeEditModal" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">✕</button>
                </div>

                <form wire:submit="saveRegistrationUpdate" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Status Pemasangan</label>
                        <select wire:model="editStatus" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending">Menunggu Verifikasi Data</option>
                            <option value="survey">Survey Lokasi ODP & Tiang</option>
                            <option value="scheduled">Jadwal Pemasangan Teknisi</option>
                            <option value="installed">Terpasang & Aktif (Selesai)</option>
                            <option value="rejected">Dibatalkan / Di Luar Jangkauan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Rencana / Tanggal Pemasangan</label>
                        <input wire:model="editPreferredDate" type="date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">Titik Koordinat Lokasi Pemasangan</label>
                            @if ($editLatitude && $editLongitude)
                                <a href="https://www.google.com/maps?q={{ $editLatitude }},{{ $editLongitude }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline text-xs font-semibold flex items-center gap-1">
                                    <span>Buka di Google Maps</span>
                                    <span>↗</span>
                                </a>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-[10px] text-slate-500 font-semibold block mb-0.5">Latitude</label>
                                <input wire:model="editLatitude" type="text" placeholder="-6.xxx" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-semibold block mb-0.5">Longitude</label>
                                <input wire:model="editLongitude" type="text" placeholder="106.xxx" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Catatan Teknisi / Tindak Lanjut Admin</label>
                        <textarea wire:model="editAdminNotes" rows="3" placeholder="Contoh: Tim teknisi Joko terjadwal pasang jam 10 pagi. Port ODP nomor 4." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Status Pembayaran Komisi Pembawa Pelanggan</label>
                        <select wire:model="editCommissionStatus" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="unpaid">Belum Dibayar (Unpaid)</option>
                            <option value="paid">Sudah Ditransfer / Dicairkan (Paid)</option>
                            <option value="cancelled">Dibatalkan (Cancelled)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                        <button type="button" wire:click="closeEditModal" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-md shadow-blue-500/25 transition cursor-pointer disabled:opacity-50">
                            <span wire:loading.remove>Simpan Perubahan</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

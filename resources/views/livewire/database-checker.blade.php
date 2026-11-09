<div class="space-y-6" @if($autoRefresh) wire:poll.5s="refreshDefault" @endif>
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                <span>Pengecek Koneksi Database</span>
                @if(($defaultResult['connected'] ?? false))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Terhubung
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        Terputus
                    </span>
                @endif
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Diagnostik, pengujian latency, inspeksi tabel, dan penguji koneksi database real-time.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Real-time Clock Widget -->
            <div 
                x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB' }" 
                x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB', 1000)"
                class="inline-flex items-center gap-2 px-3 py-2 bg-slate-100 dark:bg-slate-800/80 rounded-xl text-xs font-mono font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 shadow-2xs"
            >
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span>Waktu Sekarang: <strong class="text-slate-900 dark:text-white" x-text="time"></strong></span>
            </div>

            <!-- Auto Refresh Toggle -->
            <button 
                type="button"
                wire:click="toggleAutoRefresh"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-xl border transition-colors cursor-pointer {{ $autoRefresh ? 'bg-emerald-50 text-emerald-700 border-emerald-300 dark:bg-emerald-950/60 dark:text-emerald-400 dark:border-emerald-800 font-semibold' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}"
            >
                <span class="w-2 h-2 rounded-full {{ $autoRefresh ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                <span>Auto-Refresh (5s)</span>
            </button>

            <!-- Manual Refresh Button -->
            <button 
                wire:click="refreshDefault" 
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white text-xs font-medium rounded-xl shadow-sm transition-all duration-150 disabled:opacity-50 cursor-pointer"
            >
                <svg wire:loading.class="animate-spin" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Perbarui Status</span>
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-2 overflow-x-auto">
        <button 
            wire:click="setTab('default')"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors flex items-center gap-2 {{ $activeTab === 'default' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/70 dark:text-blue-400 font-semibold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span>Koneksi Default (.env)</span>
        </button>

        <button 
            wire:click="setTab('custom')"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors flex items-center gap-2 {{ $activeTab === 'custom' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/70 dark:text-blue-400 font-semibold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
            </svg>
            <span>Penguji Kustom (Live Tester)</span>
        </button>

        <button 
            wire:click="setTab('tables')"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors flex items-center gap-2 {{ $activeTab === 'tables' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/70 dark:text-blue-400 font-semibold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
            <span>Daftar Tabel ({{ $defaultResult['tables_count'] ?? 0 }})</span>
        </button>

        <button 
            wire:click="setTab('environment')"
            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors flex items-center gap-2 {{ $activeTab === 'environment' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/70 dark:text-blue-400 font-semibold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>Info Lingkungan</span>
        </button>
    </div>

    <!-- TAB 1: KONEKSI DEFAULT -->
    @if($activeTab === 'default')
        <div class="space-y-6">
            <!-- Stat Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Status Card -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl {{ ($defaultResult['connected'] ?? false) ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400' }} flex items-center justify-center shrink-0">
                        @if(($defaultResult['connected'] ?? false))
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Status Koneksi</div>
                        <div class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ ($defaultResult['connected'] ?? false) ? 'Terhubung' : 'Gagal' }}
                        </div>
                    </div>
                </div>

                <!-- Latency Card -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Latency / Response</div>
                        <div class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ $defaultResult['latency_ms'] ?? 0 }} <span class="text-xs font-normal text-slate-500">ms</span>
                        </div>
                    </div>
                </div>

                <!-- Server Version Card -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Versi Engine / DB</div>
                        <div class="text-base font-bold text-slate-900 dark:text-white truncate max-w-[140px]" title="{{ $defaultResult['server_version'] ?? 'N/A' }}">
                            {{ $defaultResult['server_version'] ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <!-- Table Count Card -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Jumlah Tabel</div>
                        <div class="text-lg font-bold text-slate-900 dark:text-white">
                            {{ $defaultResult['tables_count'] ?? 0 }} <span class="text-xs font-normal text-slate-500">tabel</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Banner If Not Connected -->
            @if(!($defaultResult['connected'] ?? false) && isset($defaultResult['error']))
                <div class="p-6 rounded-2xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/80 text-rose-900 dark:text-rose-200 space-y-3">
                    <div class="flex items-center gap-2 font-bold text-rose-700 dark:text-rose-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span>Gagal Terhubung ke Database!</span>
                    </div>
                    <p class="text-sm font-mono bg-rose-100/70 dark:bg-rose-900/50 p-3 rounded-lg border border-rose-200/80 dark:border-rose-800 break-words">
                        {{ $defaultResult['error']['message'] }}
                    </p>
                    @if(!empty($defaultResult['error']['hint']))
                        <div class="flex items-start gap-2 text-sm text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 p-3 rounded-lg border border-slate-200 dark:border-slate-800">
                            <span class="font-bold text-amber-600 dark:text-amber-400">💡 Saran:</span>
                            <span>{{ $defaultResult['error']['hint'] }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Main Specs & Ping Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Configuration Details -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-between">
                        <span>Detail Konfigurasi Aktif</span>
                        <span class="text-xs font-normal text-slate-400">Diambil dari config/database.php</span>
                    </h2>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        <div class="py-3 flex justify-between items-center">
                            <span class="text-slate-500 dark:text-slate-400">Driver Database</span>
                            <span class="font-mono font-semibold px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 uppercase">
                                {{ $defaultResult['driver'] ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <span class="text-slate-500 dark:text-slate-400">Host & Port</span>
                            <span class="font-mono font-medium text-slate-900 dark:text-white">
                                {{ $defaultResult['host'] ?? 'localhost' }}:{{ $defaultResult['port'] ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <span class="text-slate-500 dark:text-slate-400">Nama Database / Path</span>
                            <span class="font-mono font-medium text-slate-900 dark:text-white text-right break-all max-w-[280px]">
                                {{ $defaultResult['database'] ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <span class="text-slate-500 dark:text-slate-400">Username Database</span>
                            <span class="font-mono font-medium text-slate-900 dark:text-white">
                                {{ $defaultResult['username'] ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <span class="text-slate-500 dark:text-slate-400">Ukuran File Database</span>
                            <span class="font-medium text-slate-900 dark:text-white">
                                {{ $defaultResult['database_size'] ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <span class="text-slate-500 dark:text-slate-400">Waktu Pengujian Terakhir</span>
                            <span class="font-semibold text-blue-600 dark:text-blue-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $defaultResult['tested_at'] ?? 'N/A' }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Ping & Quick Test Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white mb-2">Uji Coba Query Cepat</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                            Eksekusi query pengujian sederhana (<code class="font-mono text-blue-600 dark:text-blue-400">SELECT datetime()</code>) untuk menguji throughput dan waktu server database.
                        </p>

                        <button 
                            wire:click="runPingQuery" 
                            wire:loading.attr="disabled"
                            class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl shadow-sm transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                        >
                            <svg wire:loading.class="animate-spin" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Jalankan Ping Query</span>
                        </button>

                        @if($pingQueryResult)
                            <div class="mt-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-1">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-600 dark:text-slate-300">
                                    <span>Hasil Eksekusi:</span>
                                    <span class="text-blue-600 dark:text-blue-400">{{ $pingQueryLatency }} ms</span>
                                </div>
                                <div class="text-xs font-mono text-slate-800 dark:text-slate-200 break-words">
                                    {{ $pingQueryResult }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 mt-4">
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Timezone Aplikasi: <strong class="text-slate-700 dark:text-slate-300">{{ config('app.timezone', 'Asia/Jakarta') }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 2: PENGUJI KUSTOM -->
    @if($activeTab === 'custom')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Input Form -->
            <div class="lg:col-span-7 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Uji Koneksi Database Kustom</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                    Uji koneksi ke database eksternal atau konfigurasi baru tanpa mengubah file konfigurasi <code class="font-mono text-blue-600">.env</code>.
                </p>

                <form wire:submit="testCustom" class="space-y-4">
                    <!-- Driver Selector -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5">
                            Driver Database
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach(['mysql' => 'MySQL / MariaDB', 'sqlite' => 'SQLite', 'pgsql' => 'PostgreSQL', 'sqlsrv' => 'SQL Server'] as $key => $label)
                                <button 
                                    type="button" 
                                    wire:click="$set('customDriver', '{{ $key }}')"
                                    class="p-2.5 text-xs font-semibold rounded-xl border text-center transition-all cursor-pointer {{ $customDriver === $key ? 'border-blue-600 bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400 dark:border-blue-500 shadow-sm' : 'border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @if($customDriver === 'sqlite')
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5">
                                Path Database File / Memory
                            </label>
                            <input 
                                type="text" 
                                wire:model="customDatabase" 
                                placeholder="C:\path\to\database.sqlite atau :memory:"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            @error('customDatabase') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5">
                                    Host / Server
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="customHost" 
                                    placeholder="127.0.0.1 atau localhost"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('customHost') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5">
                                    Port
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="customPort" 
                                    placeholder="3306"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('customPort') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5">
                                Nama Database <span class="text-slate-400 font-normal lowercase">(opsional untuk cek koneksi server)</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="customDatabase" 
                                placeholder="misal: laravel, db_toko, dsb."
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5">
                                    Username
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="customUsername" 
                                    placeholder="root"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('customUsername') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5">
                                    Password
                                </label>
                                <input 
                                    type="password" 
                                    wire:model="customPassword" 
                                    placeholder="Kosongkan jika tanpa password"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                        </div>
                    @endif

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-500/20 transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                        >
                            <svg wire:loading.class="animate-spin" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Uji Koneksi Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Result Box -->
            <div class="lg:col-span-5 flex flex-col">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm flex-1 flex flex-col">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4">Hasil Pengujian Kustom</h2>

                    @if($customResult)
                        @if($customResult['connected'])
                            <div class="p-5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-emerald-800 dark:text-emerald-300">Koneksi Berhasil!</div>
                                        <div class="text-xs text-emerald-600 dark:text-emerald-400">Server merespon dalam {{ $customResult['latency_ms'] }} ms</div>
                                    </div>
                                </div>

                                <div class="divide-y divide-emerald-200/60 dark:divide-emerald-900/60 text-xs text-slate-700 dark:text-slate-300 pt-2 font-mono">
                                    <div class="py-1.5 flex justify-between">
                                        <span class="text-slate-500">Driver:</span>
                                        <span class="font-semibold">{{ strtoupper($customResult['driver']) }}</span>
                                    </div>
                                    <div class="py-1.5 flex justify-between">
                                        <span class="text-slate-500">Host:Port:</span>
                                        <span>{{ $customResult['host'] }}:{{ $customResult['port'] }}</span>
                                    </div>
                                    <div class="py-1.5 flex justify-between">
                                        <span class="text-slate-500">Database:</span>
                                        <span>{{ $customResult['database'] }}</span>
                                    </div>
                                    <div class="py-1.5 flex justify-between">
                                        <span class="text-slate-500">Versi Server:</span>
                                        <span>{{ $customResult['server_version'] }}</span>
                                    </div>
                                    <div class="py-1.5 flex justify-between">
                                        <span class="text-slate-500">Waktu Tes:</span>
                                        <span class="font-semibold text-emerald-700 dark:text-emerald-400">{{ $customResult['tested_at'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-rose-800 dark:text-rose-300">Koneksi Gagal!</div>
                                        <div class="text-xs text-rose-600 dark:text-rose-400">Gagal terhubung (Latency: {{ $customResult['latency_ms'] }} ms)</div>
                                    </div>
                                </div>

                                <div class="p-3 rounded-lg bg-rose-100/70 dark:bg-rose-900/40 border border-rose-200 dark:border-rose-800 text-xs font-mono text-rose-900 dark:text-rose-200 break-words">
                                    {{ $customResult['error']['message'] ?? 'Unknown Error' }}
                                </div>

                                @if(!empty($customResult['error']['hint']))
                                    <div class="text-xs text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 p-3 rounded-lg border border-slate-200 dark:border-slate-700">
                                        <strong class="text-amber-600 dark:text-amber-400">💡 Saran:</strong> {{ $customResult['error']['hint'] }}
                                    </div>
                                @endif

                                <div class="text-right text-[11px] text-slate-400 font-mono">
                                    Waktu Tes: {{ $customResult['tested_at'] }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center p-8 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl">
                            <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum Ada Pengujian</div>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs">
                                Masukkan detail konfigurasi database pada form di sebelah kiri lalu klik tombol "Uji Koneksi Sekarang".
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 3: DAFTAR TABEL -->
    @if($activeTab === 'tables')
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Tabel Database</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Menampilkan semua tabel yang terdaftar pada koneksi <span class="font-mono font-semibold text-blue-600">{{ $defaultResult['connection_name'] ?? 'default' }}</span>.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                        Total: {{ $defaultResult['tables_count'] ?? 0 }} Tabel
                    </span>
                    @if($defaultResult['has_migrations'] ?? false)
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                            {{ $defaultResult['migrations_count'] }} Migrasi
                        </span>
                    @endif
                </div>
            </div>

            @if(!empty($defaultResult['tables']))
                <div class="overflow-x-auto border border-slate-200 dark:border-slate-800 rounded-xl">
                    <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-800/80 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="py-3 px-4 w-16 text-center">#</th>
                                <th class="py-3 px-4">Nama Tabel</th>
                                <th class="py-3 px-4 text-right">Tipe / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-mono text-xs">
                            @foreach($defaultResult['tables'] as $index => $table)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 px-4 text-center text-slate-400">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $table }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-right text-slate-500">
                                        @if(str_contains($table, 'migrations'))
                                            <span class="px-2 py-0.5 rounded bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300 font-sans font-medium text-[11px]">System Table</span>
                                        @elseif(str_contains($table, 'user'))
                                            <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300 font-sans font-medium text-[11px]">Auth / User</span>
                                        @else
                                            <span class="font-sans text-[11px] text-slate-400">Data Table</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 text-center text-slate-400 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                    <p>Tidak ada tabel yang ditemukan atau koneksi database belum terhubung.</p>
                </div>
            @endif
        </div>
    @endif

    <!-- TAB 4: INFORMASI LINGKUNGAN -->
    @if($activeTab === 'environment')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- PHP & Server Environment -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4">Lingkungan Server & PHP</h2>

                <div class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    <div class="py-3 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Versi PHP</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $environmentInfo['php_version'] ?? PHP_VERSION }}</span>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Versi Framework Laravel</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $environmentInfo['laravel_version'] ?? 'Laravel 13' }}</span>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Timezone Aktif</span>
                        <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $environmentInfo['timezone'] ?? config('app.timezone', 'Asia/Jakarta') }}</span>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Sistem Operasi</span>
                        <span class="font-medium text-slate-900 dark:text-white">{{ $environmentInfo['os'] ?? 'Windows' }}</span>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Memory Limit</span>
                        <span class="font-medium text-slate-900 dark:text-white">{{ $environmentInfo['memory_limit'] ?? 'N/A' }}</span>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <span class="text-slate-500 dark:text-slate-400">Max Execution Time</span>
                        <span class="font-medium text-slate-900 dark:text-white">{{ $environmentInfo['max_execution_time'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- PDO Drivers Status Matrix -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4">Dukungan Driver PDO (PHP Extensions)</h2>

                <div class="space-y-3">
                    @foreach($availablePdoDrivers as $driver => $installed)
                        <div class="p-3 rounded-xl border border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg {{ $installed ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-slate-100 text-slate-400 dark:bg-slate-800' }} flex items-center justify-center font-mono text-xs font-bold">
                                    {{ substr($driver, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-slate-900 dark:text-white">pdo_{{ $driver }}</div>
                                    <div class="text-xs text-slate-400 uppercase">{{ $driver }}</div>
                                </div>
                            </div>

                            <div>
                                @if($installed)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

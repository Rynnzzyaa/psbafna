<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ $title ?? 'Sistem Pendaftaran & Referral WiFi' }} - AFNALINK</title>
    <link rel="icon" type="image/png" href="{{ asset('images/afnalink-logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Styles / Scripts -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <style>
            [x-cloak] { display: none !important; }
            body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>
    @endif
    @livewireStyles
</head>
<body class="min-h-full flex flex-col bg-[#060b18] text-slate-100 font-sans selection:bg-amber-400 selection:text-slate-900">
    <!-- Top Demo Quick Switch Bar (Khusus mempermudah review & demo role admin & pembawa pelanggan) -->
    <div class="bg-[#0b1326] text-slate-300 text-xs py-1.5 px-4 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-400"></span>
                <span class="text-slate-400 font-medium">Quick Demo Role Switch:</span>
                <a href="{{ route('demo.login', 'admin') }}" class="px-2 py-0.5 rounded bg-blue-900/60 hover:bg-blue-800 text-blue-300 font-semibold border border-blue-700/60 transition">
                    1. Login Admin Monitoring
                </a>
                <a href="{{ route('demo.login', 'referrer') }}" class="px-2 py-0.5 rounded bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 font-semibold border border-amber-500/40 transition">
                    2. Login Pembawa Pelanggan (085878942325)
                </a>
            </div>
            <div class="hidden sm:flex items-center gap-3 text-[11px] text-slate-400">
                <a href="{{ route('db.check') }}" class="hover:text-slate-200 transition">DB Checker</a>
                <span>•</span>
                <span>AFNALINK v2.0</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="border-b border-slate-800/80 bg-[#0a1226]/90 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-full p-0.5 bg-gradient-to-tr from-cyan-400 to-amber-400 flex items-center justify-center text-white shadow-md shadow-cyan-500/20 group-hover:scale-105 transition duration-200">
                            <img src="{{ asset('images/afnalink-logo.png') }}" alt="AFNALINK Logo" class="w-full h-full object-contain rounded-full">
                        </div>
                        <div>
                            <span class="font-extrabold text-lg tracking-wide text-white block leading-none">AFNALINK</span>
                            <span class="text-[10px] text-cyan-400 font-semibold tracking-wider uppercase">teman ketika kamu Healing</span>
                        </div>
                    </a>

                    <!-- Nav items -->
                    <nav class="hidden md:flex items-center gap-1 text-xs font-semibold">
                        <a href="{{ route('register.wifi') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('register.wifi', 'home') ? 'bg-blue-950/80 text-cyan-300 border border-blue-800/50' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition">
                            Form Pendaftaran
                        </a>
                        <a href="{{ route('tracking') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('tracking') ? 'bg-blue-950/80 text-cyan-300 border border-blue-800/50' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition">
                            Lacak Status
                        </a>
                    </nav>
                </div>

                <!-- Right Actions / Auth -->
                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.monitoring') }}" class="text-xs font-bold px-3.5 py-2 rounded-xl {{ request()->routeIs('admin.monitoring') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'bg-blue-950 text-blue-300 border border-blue-800 hover:bg-blue-900' }} transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Monitoring Admin</span>
                            </a>
                        @else
                            <a href="{{ route('referrer.dashboard') }}" class="text-xs font-bold px-3.5 py-2 rounded-xl {{ request()->routeIs('referrer.dashboard') ? 'bg-amber-400 text-slate-950 shadow-md shadow-amber-500/25' : 'bg-amber-500/10 text-amber-300 border border-amber-500/30 hover:bg-amber-500/20' }} transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>Dashboard Saya ({{ auth()->user()->referral_code }})</span>
                            </a>
                        @endif

                        <div class="flex items-center gap-2 pl-2 border-l border-slate-800">
                            <span class="text-xs font-semibold text-slate-300 hidden md:inline-block">
                                {{ auth()->user()->name }}
                            </span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs px-2.5 py-1.5 rounded-lg border border-slate-800 hover:bg-slate-800 text-slate-300 transition cursor-pointer" title="Keluar">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('register') }}" class="text-xs font-semibold px-3 py-2 rounded-xl text-slate-300 hover:bg-slate-800/70 hover:text-white transition">
                            Daftar Jadi Mitra
                        </a>
                        <a href="{{ route('login') }}" class="text-xs font-bold px-4 py-2 rounded-xl bg-[#f5b82e] hover:bg-[#e6aa27] text-slate-950 shadow-sm shadow-amber-500/20 transition">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200 font-bold ml-2">✕</button>
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 rounded-2xl bg-rose-950/60 border border-rose-800 text-rose-300 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200 font-bold ml-2">✕</button>
            </div>
        @endif
    </div>

    <!-- Main Content Slot -->
    <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8 max-w-7xl w-full mx-auto">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 bg-[#091124] py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/afnalink-logo.png') }}" alt="AFNA" class="w-5 h-5 rounded-full">
                <span>© {{ date('Y') }} PT. AFNA DIGITAL INDONESIA • AFNALINK - teman ketika kamu Healing</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('register.wifi') }}" class="hover:text-white transition">Daftar WiFi</a>
                <a href="{{ route('tracking') }}" class="hover:text-white transition">Lacak Status</a>
                <a href="{{ route('login') }}" class="hover:text-amber-400 transition">Portal Masuk</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>

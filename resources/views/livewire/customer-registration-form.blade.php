<div class="max-w-5xl mx-auto py-4 sm:py-8 space-y-8">
    @if ($submitted)
        <!-- Registration Success Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-10 shadow-xl text-center space-y-6">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 mx-auto">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <div class="max-w-md mx-auto space-y-2">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Pendaftaran Berhasil Terkirim!</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Terima kasih telah mendaftar layanan internet WiFi kami. Tim teknisi kami akan segera menghubungi Anda untuk verifikasi dan survey lokasi.
                </p>
            </div>

            <div class="bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 max-w-md mx-auto text-left space-y-3">
                <div class="text-xs uppercase tracking-wider font-semibold text-slate-500 dark:text-slate-400">Nomor Registrasi Anda</div>
                <div class="flex items-center justify-between gap-2">
                    <span class="font-mono text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400 tracking-wider">{{ $createdRegistrationNumber }}</span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $createdRegistrationNumber }}'); alert('Nomor registrasi berhasil disalin!');" class="text-xs px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300 font-medium transition cursor-pointer">
                        Salin
                    </button>
                </div>

                @if ($latitude && $longitude)
                    <div class="pt-2 border-t border-slate-200 dark:border-slate-700 text-xs flex items-center justify-between gap-2">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Titik GPS: <strong class="font-mono text-slate-700 dark:text-slate-300">{{ $latitude }}, {{ $longitude }}</strong>
                        </span>
                        <a href="https://www.google.com/maps?q={{ $latitude }},{{ $longitude }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold text-[11px]">
                            Buka di Maps ↗
                        </a>
                    </div>
                @endif

                <div class="text-xs text-slate-500 dark:text-slate-400 pt-1 border-t border-slate-200 dark:border-slate-700">
                    Simpan nomor ini untuk melacak status pemasangan WiFi Anda di menu <a href="{{ route('tracking') }}" class="text-blue-600 font-semibold underline">Lacak Status</a>.
                </div>
            </div>

            @if ($isReferralValid)
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-medium">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Terdaftar melalui rekomendasi mitra: <strong>{{ $referrerName }}</strong> ({{ $referral_code }})</span>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-4">
                <a href="{{ route('tracking', ['q' => $createdRegistrationNumber]) }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md shadow-blue-500/25 transition">
                    Cek Status Pemasangan
                </a>
                <button type="button" wire:click="resetForm" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-sm transition cursor-pointer">
                    Daftar Pelanggan Lain
                </button>
            </div>
        </div>
    @else
        <!-- Hero Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-indigo-700 to-slate-900 text-white p-6 sm:p-10 shadow-xl">
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 max-w-2xl space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-blue-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Jaringan Fiber Optic 100% Ultra Cepat
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Formulir Pendaftaran Pasang WiFi Baru</h1>
                <p class="text-blue-100 text-sm sm:text-base leading-relaxed">
                    Nikmati internet unlimited tanpa batasan kuota (FUP) dengan koneksi stabil untuk streaming, gaming, dan kerja dari rumah. Pendaftaran mudah dan cepat!
                </p>

                @if ($isReferralValid)
                    <div class="mt-4 p-3.5 rounded-2xl bg-emerald-900/60 border border-emerald-400/40 text-emerald-100 text-xs sm:text-sm flex items-center gap-3 shadow-lg">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/30 flex items-center justify-center shrink-0 text-emerald-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="font-bold text-white block">Direferensikan oleh Pembawa Pelanggan Resmi:</span>
                            <span class="text-emerald-200">{{ $referrerName }} (Kode: <span class="font-mono font-semibold">{{ $referral_code }}</span>)</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <form wire:submit="submitRegistration" class="space-y-8">
            <!-- Step 1: Paket WiFi -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-bold text-sm flex items-center justify-center">1</span>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Pilih Paket Internet WiFi</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Pilih kecepatan yang sesuai dengan kebutuhan tempat tinggal atau usaha Anda</p>
                        </div>
                    </div>
                </div>

                @error('selectedPackageId')
                    <div class="p-3 rounded-xl bg-rose-50 text-rose-700 text-xs border border-rose-200">{{ $message }}</div>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($packages as $pkg)
                        <div 
                            wire:click="selectPackage({{ $pkg->id }})"
                            class="relative cursor-pointer rounded-2xl border-2 p-5 transition-all duration-200 flex flex-col justify-between {{ $selectedPackageId === $pkg->id ? 'border-blue-600 bg-blue-50/40 dark:bg-blue-950/20 shadow-md ring-2 ring-blue-600/20' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 bg-white dark:bg-slate-800/50' }}"
                        >
                            @if ($pkg->is_popular)
                                <span class="absolute -top-3 left-4 text-[10px] uppercase font-bold tracking-wider px-2.5 py-0.5 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-sm">
                                    Paling Diminati
                                </span>
                            @endif

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-slate-900 dark:text-white text-base">{{ $pkg->name }}</h3>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $selectedPackageId === $pkg->id ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 dark:border-slate-600' }}">
                                        @if ($selectedPackageId === $pkg->id)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-baseline gap-1">
                                    <span class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $pkg->speed_mbps }}</span>
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Mbps</span>
                                </div>

                                <div class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                    {{ $pkg->formattedPrice() }}
                                </div>

                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                    {{ $pkg->description }}
                                </p>

                                @if (!empty($pkg->features))
                                    <ul class="text-xs text-slate-600 dark:text-slate-300 space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-700/60">
                                        @foreach (array_slice($pkg->features, 0, 3) as $feat)
                                            <li class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="truncate">{{ $feat }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <div class="mt-4 pt-3">
                                <span class="block w-full text-center py-2 rounded-xl text-xs font-semibold transition {{ $selectedPackageId === $pkg->id ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700/80 text-slate-700 dark:text-slate-300 hover:bg-slate-200' }}">
                                    {{ $selectedPackageId === $pkg->id ? '✓ Paket Terpilih' : 'Pilih Paket' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Step 2: Data Diri & Lokasi Pemasangan (Termasuk Tag GPS & Manual) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
                    <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-bold text-sm flex items-center justify-center">2</span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Data Diri & Alamat Pemasangan</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pastikan nomor telepon WhatsApp aktif dan tentukan titik lokasi akurat rumah Anda</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="customer_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Nama Lengkap Pemesan <span class="text-rose-500">*</span></label>
                        <input wire:model="customer_name" type="text" id="customer_name" placeholder="Sesuai KTP / Nama Pemilik Rumah" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @error('customer_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="customer_phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">No. WhatsApp / HP Aktif <span class="text-rose-500">*</span></label>
                        <input wire:model="customer_phone" type="text" id="customer_phone" placeholder="Contoh: 08123456789" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @error('customer_phone') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="customer_email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Alamat Email (Opsional)</label>
                        <input wire:model="customer_email" type="email" id="customer_email" placeholder="Untuk pengiriman invoice bulanan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @error('customer_email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="customer_nik" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">NIK KTP (Opsional)</label>
                        <input wire:model="customer_nik" type="text" id="customer_nik" placeholder="16 Digit No. KTP" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @error('customer_nik') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="space-y-4 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <div>
                        <label for="installation_address" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Alamat Lengkap Pemasangan <span class="text-rose-500">*</span></label>
                        <textarea wire:model="installation_address" id="installation_address" rows="2" placeholder="Nama Jalan, No. Rumah, RT/RW, Patokan terdekat (misal: depan warung Bu Siti)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"></textarea>
                        @error('installation_address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="city" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                            <input wire:model="city" type="text" id="city" placeholder="Contoh: Jakarta Selatan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @error('city') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="district" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Kecamatan <span class="text-rose-500">*</span></label>
                            <input wire:model="district" type="text" id="district" placeholder="Contoh: Kebayoran Baru" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @error('district') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Kode Pos</label>
                            <input wire:model="postal_code" type="text" id="postal_code" placeholder="12xxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- FITUR TAG LOKASI GPS OTOMATIS & MANUAL DENGAN PETA INTERAKTIF -->
                    <!-- ============================================== -->
                    <div 
                        x-data="{
                            lat: @entangle('latitude'),
                            lng: @entangle('longitude'),
                            accuracy: @entangle('locationAccuracy'),
                            isLocating: false,
                            gpsMessage: '',
                            gpsError: '',
                            map: null,
                            marker: null,

                            initMap() {
                                this.$nextTick(() => {
                                    if (this.map) return;
                                    const defaultLat = this.lat ? parseFloat(this.lat) : -6.2088;
                                    const defaultLng = this.lng ? parseFloat(this.lng) : 106.8456;
                                    const zoom = this.lat ? 17 : 12;

                                    this.map = L.map(this.$refs.mapContainer).setView([defaultLat, defaultLng], zoom);

                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        maxZoom: 19,
                                        attribution: '&copy; OpenStreetMap'
                                    }).addTo(this.map);

                                    const pinIcon = L.icon({
                                        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                                        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                                        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                                        iconSize: [25, 41],
                                        iconAnchor: [12, 41],
                                        popupAnchor: [1, -34],
                                        shadowSize: [41, 41]
                                    });

                                    this.marker = L.marker([defaultLat, defaultLng], {
                                        draggable: true,
                                        icon: pinIcon
                                    }).addTo(this.map);

                                    if (this.lat && this.lng) {
                                        this.marker.bindPopup('<b>Titik Pasang Terpilih</b><br>' + this.lat + ', ' + this.lng).openPopup();
                                    }

                                    // Manual Click on Map
                                    this.map.on('click', (e) => {
                                        this.setCoords(e.latlng.lat, e.latlng.lng, 'Manual (Klik pada Peta)');
                                    });

                                    // Manual Drag Marker
                                    this.marker.on('dragend', (e) => {
                                        const pos = e.target.getLatLng();
                                        this.setCoords(pos.lat, pos.lng, 'Manual (Geser Pin)');
                                    });

                                    setTimeout(() => {
                                        this.map.invalidateSize();
                                    }, 400);
                                });
                            },

                            setCoords(newLat, newLng, source) {
                                const formattedLat = parseFloat(newLat).toFixed(6);
                                const formattedLng = parseFloat(newLng).toFixed(6);
                                this.lat = formattedLat;
                                this.lng = formattedLng;
                                this.gpsMessage = source ? 'Lokasi ditentukan via: ' + source : '';
                                this.gpsError = '';

                                this.marker.setLatLng([newLat, newLng]);
                                this.marker.bindPopup('<b>Titik Pasang Terpilih</b><br>' + formattedLat + ', ' + formattedLng).openPopup();
                                this.map.panTo([newLat, newLng]);
                            },

                            getGpsLocation() {
                                if (!navigator.geolocation) {
                                    this.gpsError = 'Browser perangkat Anda tidak mendukung deteksi GPS.';
                                    return;
                                }

                                this.isLocating = true;
                                this.gpsError = '';
                                this.gpsMessage = 'Sedang mencari koordinat satelit GPS...';

                                navigator.geolocation.getCurrentPosition(
                                    (pos) => {
                                        this.isLocating = false;
                                        const crd = pos.coords;
                                        this.accuracy = Math.round(crd.accuracy);
                                        this.setCoords(crd.latitude, crd.longitude, 'GPS Otomatis (Akurasi: ±' + this.accuracy + ' meter)');
                                        this.map.setView([crd.latitude, crd.longitude], 17);

                                        // Auto reverse geocode to assist filling district and city
                                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${crd.latitude}&lon=${crd.longitude}&accept-language=id`)
                                            .then(r => r.json())
                                            .then(data => {
                                                if (data && data.address) {
                                                    const a = data.address;
                                                    if (!this.$wire.district && (a.suburb || a.village || a.city_district)) {
                                                        this.$wire.set('district', a.suburb || a.village || a.city_district);
                                                    }
                                                    if (!this.$wire.city && (a.city || a.town || a.county)) {
                                                        this.$wire.set('city', a.city || a.town || a.county);
                                                    }
                                                    if (!this.$wire.postal_code && a.postcode) {
                                                        this.$wire.set('postal_code', a.postcode);
                                                    }
                                                }
                                            })
                                            .catch(() => {});
                                    },
                                    (err) => {
                                        this.isLocating = false;
                                        if (err.code === 1) {
                                            this.gpsError = 'Akses izin lokasi ditolak. Silakan izinkan browser atau klik titik pada peta secara manual.';
                                        } else if (err.code === 2) {
                                            this.gpsError = 'Sinyal GPS tidak terdeteksi. Silakan klik titik rumah Anda pada peta secara manual.';
                                        } else if (err.code === 3) {
                                            this.gpsError = 'Waktu pencarian sinyal GPS habis. Silakan coba lagi atau geser pin manual.';
                                        } else {
                                            this.gpsError = 'Gagal mendeteksi lokasi GPS: ' + err.message;
                                        }
                                    },
                                    { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
                                );
                            },

                            applyManualInput() {
                                if (this.lat && this.lng) {
                                    this.setCoords(this.lat, this.lng, 'Input Manual Koordinat');
                                    this.map.setView([this.lat, this.lng], 16);
                                }
                            },

                            resetCoords() {
                                this.lat = null;
                                this.lng = null;
                                this.accuracy = null;
                                this.gpsMessage = '';
                                this.gpsError = '';
                                if (this.marker) {
                                    this.marker.closePopup();
                                }
                            }
                        }"
                        x-init="initMap()"
                        class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 space-y-4"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                    📍 Tag Lokasi Pemasangan (Titik Koordinat GPS / Manual)
                                </label>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Membantu teknisi mengetahui letak persis tiang ODP dan rumah Anda untuk mempercepat survey
                                </p>
                            </div>

                            <!-- Tombol GPS Otomatis -->
                            <div class="flex items-center gap-2">
                                <button 
                                    type="button" 
                                    @click="getGpsLocation()" 
                                    :disabled="isLocating"
                                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
                                >
                                    <svg x-show="!isLocating" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <svg x-show="isLocating" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="isLocating ? 'Mencari GPS...' : '📍 Ambil Lokasi Saya (GPS Otomatis)'"></span>
                                </button>

                                <button 
                                    type="button" 
                                    x-show="lat && lng" 
                                    @click="resetCoords()" 
                                    class="px-2.5 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-xs hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer"
                                    title="Hapus Titik"
                                >
                                    Reset
                                </button>
                            </div>
                        </div>

                        <!-- Notifikasi Status GPS -->
                        <div x-show="gpsMessage" class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span x-text="gpsMessage"></span>
                        </div>

                        <div x-show="gpsError" class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span x-text="gpsError"></span>
                        </div>

                        <!-- Interactive Map Container -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span>💡 <strong>Tips Manual:</strong> Anda dapat menggeser pin biru pada peta atau klik di titik atap rumah Anda</span>
                                <span x-show="lat" class="font-mono text-emerald-600 font-semibold" x-text="'Pin aktif: ' + lat + ', ' + lng"></span>
                            </div>
                            <div wire:ignore class="rounded-2xl overflow-hidden border border-slate-300 dark:border-slate-700 shadow-inner">
                                <div x-ref="mapContainer" class="h-64 sm:h-72 w-full z-10" style="min-height: 250px;"></div>
                            </div>
                        </div>

                        <!-- Manual Coordinates Inputs -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Latitude (Garis Lintang)</label>
                                <input 
                                    type="text" 
                                    x-model="lat" 
                                    @change="applyManualInput()"
                                    placeholder="-6.2088..." 
                                    class="w-full px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-xs focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Longitude (Garis Bujur)</label>
                                <input 
                                    type="text" 
                                    x-model="lng" 
                                    @change="applyManualInput()"
                                    placeholder="106.8456..." 
                                    class="w-full px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-xs focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        </div>

                        <!-- Status badge / External Link -->
                        <template x-if="lat && lng">
                            <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-200 dark:border-slate-700">
                                <span class="text-emerald-700 dark:text-emerald-400 font-semibold flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Titik lokasi siap dikirim bersama formulir
                                </span>
                                <a 
                                    :href="'https://www.google.com/maps?q=' + lat + ',' + lng" 
                                    target="_blank" 
                                    class="text-blue-600 dark:text-blue-400 hover:underline font-semibold flex items-center gap-1"
                                >
                                    <span>Lihat di Google Maps</span>
                                    <span>↗</span>
                                </a>
                            </div>
                        </template>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="preferred_installation_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Rencana Tanggal Pemasangan</label>
                            <input wire:model="preferred_installation_date" type="date" id="preferred_installation_date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            @error('preferred_installation_date') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="customer_notes" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Catatan Tambahan (Opsional)</label>
                            <input wire:model="customer_notes" type="text" id="customer_notes" placeholder="Misal: Pasang siang hari, atau info titik router" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Kode Referral Pembawa Pelanggan -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
                    <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold text-sm flex items-center justify-center">3</span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Kode Referral (Pembawa Pelanggan)</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Masukkan kode referral teman/agen/sales yang merekomendasikan Anda (opsional)</p>
                    </div>
                </div>

                <div class="max-w-md space-y-2">
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input 
                                wire:model="referral_code" 
                                wire:keydown.enter.prevent="checkReferralCode" 
                                type="text" 
                                placeholder="Contoh: BUDI2026 atau SITIWIFI" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-mono uppercase placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                            >
                        </div>
                        <button 
                            type="button" 
                            wire:click="checkReferralCode" 
                            class="px-4 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 text-xs font-semibold transition cursor-pointer"
                        >
                            Verifikasi
                        </button>
                    </div>

                    @if ($referralCheckMessage)
                        <div class="p-3 rounded-xl text-xs flex items-center gap-2 {{ $isReferralValid ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800' : 'bg-amber-50 text-amber-800 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800' }}">
                            @if ($isReferralValid)
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            <span>{{ $referralCheckMessage }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Step 4: Syarat & Ketentuan Berlangganan (Komitmen Minimal 18 Bulan) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm space-y-5">
                <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
                    <span class="w-8 h-8 rounded-xl bg-amber-500 text-white font-bold text-sm flex items-center justify-center">4</span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Syarat & Ketentuan Berlangganan</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Harap baca dan setujui ketentuan kontrak layanan WiFiNet Fiber sebelum mengirim formulir</p>
                    </div>
                </div>

                <!-- Highlight Box Komitmen 18 Bulan -->
                <div class="p-4 sm:p-5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border-2 border-amber-300 dark:border-amber-800 space-y-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </span>
                        <div>
                            <span class="text-xs uppercase font-extrabold tracking-wider text-amber-800 dark:text-amber-300 block">Ketentuan Utama Masa Kontrak</span>
                            <span class="text-sm sm:text-base font-bold text-amber-950 dark:text-amber-100">Komitmen Minimal Berlangganan Selama 18 (Delapan Belas) Bulan</span>
                        </div>
                    </div>

                    <div class="text-xs text-amber-900 dark:text-amber-200/90 space-y-2 pt-2 border-t border-amber-200 dark:border-amber-800/80 leading-relaxed">
                        <ul class="list-disc pl-5 space-y-1.5">
                            <li><strong>Masa Berlangganan:</strong> Pelanggan bersedia dan berkomitmen untuk berlangganan layanan internet WiFiNet Fiber secara aktif minimal selama <strong>18 bulan berturut-turut</strong> terhitung sejak tanggal aktivasi teknisi.</li>
                            <li><strong>Pemutusan Sebelum 18 Bulan:</strong> Apabila terjadi pemutusan atau pembatalan layanan secara sepihak oleh pelanggan sebelum masa kontrak 18 bulan berakhir, pelanggan bersedia dikenakan biaya penalti administrasi / pelunasan masa kontrak awal.</li>
                            <li><strong>Peminjaman Perangkat:</strong> Modem ONT / Router WiFi dipinjamkan tanpa biaya tambahan selama masa berlangganan aktif dan merupakan aset milik penyedia layanan yang wajib dikembalikan dalam kondisi baik jika berhenti berlangganan.</li>
                            <li><strong>Jaringan & Kuota:</strong> Koneksi fiber optic unlimited 100% tanpa batas kuota FUP (*True Unlimited*) dengan kestabilan sesuai paket yang dipilih.</li>
                            <li><strong>Survey & Instalasi:</strong> Tim teknisi akan menghubungi nomor WhatsApp terdaftar untuk konfirmasi jadwal survey ODP dan penarikan kabel fiber ke titik rumah Anda.</li>
                        </ul>
                    </div>
                </div>

                <!-- Checkbox Persetujuan -->
                <div class="pt-2">
                    <label class="flex items-start gap-3 p-4 sm:p-5 rounded-2xl border-2 transition cursor-pointer select-none {{ $terms_agreed ? 'bg-blue-50/80 dark:bg-blue-950/40 border-blue-500 shadow-sm' : 'bg-slate-50 dark:bg-slate-800/50 border-slate-300 dark:border-slate-700 hover:border-slate-400' }}">
                        <input 
                            wire:model.live="terms_agreed" 
                            type="checkbox" 
                            id="terms_agreed" 
                            class="mt-1 w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer shrink-0"
                        >
                        <div class="text-xs sm:text-sm text-slate-800 dark:text-slate-200">
                            <span class="font-bold block text-slate-900 dark:text-white mb-0.5">Saya Menyetujui Seluruh Syarat & Ketentuan di Atas</span>
                            <span>Saya mengonfirmasi bahwa seluruh data yang saya masukkan adalah benar dan saya menyetujui komitmen <strong>minimal masa berlangganan selama 18 bulan</strong>.</span>
                        </div>
                    </label>

                    @error('terms_agreed')
                        <div class="p-3 mt-2 rounded-xl bg-rose-50 text-rose-700 text-xs border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-900 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Submit Section -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-6 rounded-3xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                <div class="text-xs text-slate-600 dark:text-slate-400">
                    @if ($terms_agreed)
                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Syarat & ketentuan 18 bulan telah disetujui. Formulir siap dikirim!
                        </span>
                    @else
                        <span class="text-amber-700 dark:text-amber-300 font-medium flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Harap centang persetujuan syarat 18 bulan di atas untuk membuka tombol kirim formulir.
                        </span>
                    @endif
                </div>

                <button 
                    type="submit" 
                    wire:loading.attr="disabled" 
                    @if (!$terms_agreed) disabled @endif
                    class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-bold text-sm transition duration-200 flex items-center justify-center gap-2 {{ $terms_agreed ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/30 cursor-pointer' : 'bg-slate-300 dark:bg-slate-700 text-slate-500 dark:text-slate-400 cursor-not-allowed opacity-60' }}"
                >
                    <span wire:loading.remove>Kirim Formulir Pendaftaran</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses Data...
                    </span>
                </button>
            </div>
        </form>
    @endif
</div>

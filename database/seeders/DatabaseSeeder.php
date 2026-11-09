<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WifiPackage;
use App\Models\WifiRegistration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@wifinet.id'],
            [
                'name' => 'Super Administrator (Monitoring)',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'referral_code' => null,
                'phone' => '081122334455',
            ]
        );

        // 2. Seed Pembawa Pelanggan (Referrers)
        $budi = User::firstOrCreate(
            ['email' => 'budi@sales.id'],
            [
                'name' => 'Budi Santoso (Mitra Sales)',
                'role' => 'referrer',
                'referral_code' => 'BUDI2026',
                'phone' => '081234567890',
                'bank_info' => 'BCA - 8820192831 a.n Budi Santoso',
                'password' => Hash::make('password'),
            ]
        );

        $siti = User::firstOrCreate(
            ['email' => 'siti@sales.id'],
            [
                'name' => 'Siti Rahma (Mitra Komunitas)',
                'role' => 'referrer',
                'referral_code' => 'SITIWIFI',
                'phone' => '089876543210',
                'bank_info' => 'Mandiri - 132001928381 a.n Siti Rahma',
                'password' => Hash::make('password'),
            ]
        );

        $afnaUser = User::firstOrCreate(
            ['phone' => '085878942325'],
            [
                'name' => 'Mitra AFNALINK',
                'email' => 'user@afna.link',
                'role' => 'referrer',
                'referral_code' => 'AFNA2026',
                'bank_info' => 'BCA - 085878942325 a.n Mitra AFNA',
                'password' => Hash::make('password'),
            ]
        );

        // 3. Seed Wifi Packages
        $pkgBasic = WifiPackage::firstOrCreate(
            ['slug' => 'paket-basic-20mbps'],
            [
                'name' => 'Paket Basic 20 Mbps',
                'speed_mbps' => 20,
                'price' => 175000,
                'commission_reward' => 50000,
                'description' => 'Cocok untuk kebutuhan internet browsing, chatting, dan media sosial di rumah kecil.',
                'features' => [
                    'Kecepatan download up to 20 Mbps',
                    'Internet Unlimited tanpa FUP',
                    'Gratis sewa modem Wi-Fi ONT',
                    'Ideal untuk 3-5 perangkat',
                ],
                'is_popular' => false,
                'is_active' => true,
            ]
        );

        $pkgFamily = WifiPackage::firstOrCreate(
            ['slug' => 'paket-family-50mbps'],
            [
                'name' => 'Paket Family 50 Mbps',
                'speed_mbps' => 50,
                'price' => 250000,
                'commission_reward' => 75000,
                'description' => 'Paling disukai keluarga! Streaming video HD lancar, WFH, dan sekolah online tanpa buffering.',
                'features' => [
                    'Kecepatan download up to 50 Mbps',
                    'Internet 100% Fiber Optic murni',
                    'Dual-band Router Wi-Fi (2.4 & 5 GHz)',
                    'Ideal untuk 5-10 perangkat',
                    'Bebas biaya pemasangan',
                ],
                'is_popular' => true,
                'is_active' => true,
            ]
        );

        $pkgGamer = WifiPackage::firstOrCreate(
            ['slug' => 'paket-gamer-100mbps'],
            [
                'name' => 'Paket Gamer 100 Mbps',
                'speed_mbps' => 100,
                'price' => 375000,
                'commission_reward' => 100000,
                'description' => 'Kecepatan simetris dengan latensi super rendah untuk gaming kompetitif dan live streaming.',
                'features' => [
                    'Kecepatan 100 Mbps simetris 1:1',
                    'Low Ping Gaming Optimizer',
                    'Wi-Fi 6 Router Gigabit',
                    'Dukungan teknisi prioritas 24 jam',
                ],
                'is_popular' => false,
                'is_active' => true,
            ]
        );

        $pkgUltra = WifiPackage::firstOrCreate(
            ['slug' => 'paket-ultra-200mbps'],
            [
                'name' => 'Paket Ultra 200 Mbps',
                'speed_mbps' => 200,
                'price' => 550000,
                'commission_reward' => 150000,
                'description' => 'Performa tertinggi untuk smart home, kantor, download berkas besar, dan konten kreator 4K.',
                'features' => [
                    'Kecepatan 200 Mbps simetris',
                    'Mesh Wi-Fi 6 System (2 unit)',
                    'Kapasitas hingga 30+ perangkat',
                    'IP Public dinamis & proteksi DDoS',
                ],
                'is_popular' => false,
                'is_active' => true,
            ]
        );

        // 4. Seed Registrations for demo data
        if (WifiRegistration::count() === 0) {
            WifiRegistration::create([
                'registration_number' => 'REG-20261101-A101',
                'user_id' => $budi->id,
                'wifi_package_id' => $pkgFamily->id,
                'customer_name' => 'Ahmad Fauzi',
                'customer_phone' => '081299887766',
                'customer_email' => 'ahmad.fauzi@example.com',
                'installation_address' => 'Jl. Merdeka No. 45, RT 02/RW 04',
                'district' => 'Kebayoran Baru',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12160',
                'preferred_installation_date' => Carbon::now()->addDays(2),
                'customer_notes' => 'Dekat masjid Al-Ikhlas, rumah pagar hitam',
                'referral_code_used' => 'BUDI2026',
                'status' => WifiRegistration::STATUS_INSTALLED,
                'admin_notes' => 'Pemasangan sukses tanggal 02 Nov. ODP Kebayoran-04 port 6.',
                'commission_amount' => 75000,
                'commission_status' => 'paid',
                'installed_at' => Carbon::now()->subDays(1),
            ]);

            WifiRegistration::create([
                'registration_number' => 'REG-20261102-B204',
                'user_id' => $budi->id,
                'wifi_package_id' => $pkgGamer->id,
                'customer_name' => 'Rian Hidayat',
                'customer_phone' => '087711223344',
                'customer_email' => 'rian.gaming@example.com',
                'installation_address' => 'Komplek Permata Indah Blok C3 No. 12',
                'district' => 'Cilandak',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12430',
                'preferred_installation_date' => Carbon::now()->addDays(1),
                'customer_notes' => 'Mohon kabel ditarik lewat plafon rapi.',
                'referral_code_used' => 'BUDI2026',
                'status' => WifiRegistration::STATUS_SCHEDULED,
                'admin_notes' => 'Jadwal instalasi besok pukul 10:00 WIB oleh tim teknisi Joni.',
                'commission_amount' => 100000,
                'commission_status' => 'unpaid',
            ]);

            WifiRegistration::create([
                'registration_number' => 'REG-20261102-C309',
                'user_id' => $budi->id,
                'wifi_package_id' => $pkgBasic->id,
                'customer_name' => 'Dewi Lestari',
                'customer_phone' => '085699443322',
                'customer_email' => 'dewi.lestari@example.com',
                'installation_address' => 'Gang Melati 2 No. 8',
                'district' => 'Tebet',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12810',
                'preferred_installation_date' => Carbon::now()->addDays(3),
                'customer_notes' => 'Bisa dipasang sore hari setelah jam kerja.',
                'referral_code_used' => 'BUDI2026',
                'status' => WifiRegistration::STATUS_SURVEY,
                'admin_notes' => 'Menunggu pengecekan jarak tiang fiber ke rumah.',
                'commission_amount' => 50000,
                'commission_status' => 'unpaid',
            ]);

            WifiRegistration::create([
                'registration_number' => 'REG-20261103-D412',
                'user_id' => $siti->id,
                'wifi_package_id' => $pkgFamily->id,
                'customer_name' => 'Hendro Wijaya',
                'customer_phone' => '081344556677',
                'customer_email' => 'hendro.w@example.com',
                'installation_address' => 'Jl. Boulevard Raya Blok AA No. 5',
                'district' => 'Kelapa Gading',
                'city' => 'Jakarta Utara',
                'postal_code' => '14240',
                'preferred_installation_date' => Carbon::now()->addDays(2),
                'customer_notes' => 'Tolong hubungi WhatsApp dulu sebelum datang.',
                'referral_code_used' => 'SITIWIFI',
                'status' => WifiRegistration::STATUS_PENDING,
                'admin_notes' => 'Baru masuk dari pendaftaran online.',
                'commission_amount' => 75000,
                'commission_status' => 'unpaid',
            ]);

            WifiRegistration::create([
                'registration_number' => 'REG-20261103-E555',
                'user_id' => null, // Pendaftaran organik tanpa referral
                'wifi_package_id' => $pkgUltra->id,
                'customer_name' => 'PT Surya Media Pratama',
                'customer_phone' => '081199008877',
                'customer_email' => 'office@suryamedia.co.id',
                'installation_address' => 'Ruko Golden Plaza Blok D-08',
                'district' => 'Fatmawati',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12420',
                'preferred_installation_date' => Carbon::now()->addDays(4),
                'customer_notes' => 'Untuk kebutuhan kantor studio video.',
                'referral_code_used' => null,
                'status' => WifiRegistration::STATUS_PENDING,
                'admin_notes' => null,
                'commission_amount' => 0,
                'commission_status' => 'cancelled',
            ]);
        }
    }
}

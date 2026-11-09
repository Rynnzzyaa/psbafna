<?php

use App\Livewire\Admin\Monitoring as AdminMonitoring;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\CustomerRegistrationForm;
use App\Livewire\CustomerStatusTracker;
use App\Livewire\Referrer\Dashboard as ReferrerDashboard;
use App\Models\User;
use App\Models\WifiPackage;
use App\Models\WifiRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    // Setup initial WiFi packages
    $this->pkgBasic = WifiPackage::create([
        'name' => 'Paket Basic 20 Mbps',
        'slug' => 'paket-basic-20mbps',
        'speed_mbps' => 20,
        'price' => 175000,
        'commission_reward' => 50000,
        'is_active' => true,
    ]);

    $this->pkgFamily = WifiPackage::create([
        'name' => 'Paket Family 50 Mbps',
        'slug' => 'paket-family-50mbps',
        'speed_mbps' => 50,
        'price' => 250000,
        'commission_reward' => 75000,
        'is_popular' => true,
        'is_active' => true,
    ]);

    // Setup Admin
    $this->admin = User::create([
        'name' => 'Admin Utama',
        'email' => 'admin@wifinet.id',
        'role' => 'admin',
        'password' => bcrypt('password'),
    ]);

    // Setup Referrer
    $this->referrer = User::create([
        'name' => 'Budi Sales',
        'email' => 'budi@sales.id',
        'role' => 'referrer',
        'referral_code' => 'BUDI2026',
        'phone' => '081234567890',
        'password' => bcrypt('password'),
    ]);
});

test('public registration page loads successfully', function (): void {
    $this->get(route('register.wifi'))
        ->assertOk()
        ->assertSee('Formulir Pendaftaran Pasang WiFi Baru')
        ->assertSee('Paket Basic 20 Mbps');
});

test('customer can register with referral code and tagged gps location', function (): void {
    Livewire::test(CustomerRegistrationForm::class, ['ref' => 'BUDI2026'])
        ->assertSet('isReferralValid', true)
        ->assertSet('referrerName', 'Budi Sales')
        ->set('selectedPackageId', $this->pkgFamily->id)
        ->set('customer_name', 'Joko Susanto')
        ->set('customer_phone', '081233445566')
        ->set('customer_email', 'joko@example.com')
        ->set('installation_address', 'Jl. Kenanga No. 12')
        ->set('city', 'Jakarta Selatan')
        ->set('district', 'Tebet')
        ->set('latitude', '-6.208763')
        ->set('longitude', '106.845599')
        ->set('terms_agreed', true)
        ->call('submitRegistration')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    $this->assertDatabaseHas('wifi_registrations', [
        'customer_name' => 'Joko Susanto',
        'user_id' => $this->referrer->id,
        'wifi_package_id' => $this->pkgFamily->id,
        'referral_code_used' => 'BUDI2026',
        'latitude' => '-6.208763',
        'longitude' => '106.845599',
        'commission_amount' => 75000,
        'status' => WifiRegistration::STATUS_PENDING,
    ]);

    $reg = WifiRegistration::where('customer_name', 'Joko Susanto')->first();
    expect($reg->hasCoordinates())->toBeTrue();
    expect($reg->googleMapsUrl())->toBe('https://www.google.com/maps?q=-6.208763,106.845599');
});

test('customer can register organically without referral', function (): void {
    Livewire::test(CustomerRegistrationForm::class)
        ->set('selectedPackageId', $this->pkgBasic->id)
        ->set('customer_name', 'Siti Organic')
        ->set('customer_phone', '085566778899')
        ->set('installation_address', 'Jl. Melati No. 5')
        ->set('city', 'Jakarta Barat')
        ->set('district', 'Palmerah')
        ->set('terms_agreed', true)
        ->call('submitRegistration')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    $this->assertDatabaseHas('wifi_registrations', [
        'customer_name' => 'Siti Organic',
        'user_id' => null,
        'referral_code_used' => null,
        'commission_amount' => 0,
        'terms_agreed' => true,
        'commitment_months' => 18,
    ]);
});

test('customer cannot register without agreeing to 18 months terms and conditions', function (): void {
    Livewire::test(CustomerRegistrationForm::class)
        ->set('selectedPackageId', $this->pkgBasic->id)
        ->set('customer_name', 'Doni Tanpa Setuju')
        ->set('customer_phone', '081299887711')
        ->set('installation_address', 'Jl. Sudirman No. 1')
        ->set('city', 'Jakarta Pusat')
        ->set('district', 'Tanah Abang')
        ->set('terms_agreed', false)
        ->call('submitRegistration')
        ->assertHasErrors(['terms_agreed' => 'accepted'])
        ->assertSet('submitted', false);

    $this->assertDatabaseMissing('wifi_registrations', [
        'customer_name' => 'Doni Tanpa Setuju',
    ]);
});

test('customer can track installation status with registration number', function (): void {
    $reg = WifiRegistration::create([
        'registration_number' => 'REG-20261103-TEST1',
        'user_id' => $this->referrer->id,
        'wifi_package_id' => $this->pkgBasic->id,
        'customer_name' => 'Bambang Tri',
        'customer_phone' => '081299881122',
        'installation_address' => 'Jl. Pahlawan No. 1',
        'city' => 'Jakarta Timur',
        'district' => 'Matraman',
        'status' => WifiRegistration::STATUS_SURVEY,
    ]);

    Livewire::test(CustomerStatusTracker::class, ['q' => 'REG-20261103-TEST1'])
        ->assertSee('Bambang Tri')
        ->assertSee('Survey Lokasi ODP');
});

test('new referrer registration generates unique referral code and role referrer', function (): void {
    Livewire::test(Register::class)
        ->set('name', 'Agus Salim')
        ->set('email', 'agus@sales.id')
        ->set('phone', '081999888777')
        ->set('bank_info', 'BCA - 1122334455')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('referrer.dashboard'));

    $user = User::where('email', 'agus@sales.id')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('referrer');
    expect($user->referral_code)->toStartWith('AGUS');
});

test('guest cannot access admin monitoring or referrer dashboard', function (): void {
    $this->get(route('admin.monitoring'))
        ->assertRedirect(route('login'));

    $this->get(route('referrer.dashboard'))
        ->assertRedirect(route('login'));
});

test('referrer cannot access admin monitoring', function (): void {
    $this->actingAs($this->referrer)
        ->get(route('admin.monitoring'))
        ->assertForbidden();
});

test('admin can access admin monitoring and update registration status', function (): void {
    $reg = WifiRegistration::create([
        'registration_number' => 'REG-20261103-ADM1',
        'user_id' => $this->referrer->id,
        'wifi_package_id' => $this->pkgBasic->id,
        'customer_name' => 'Calon Pelanggan',
        'customer_phone' => '081200000001',
        'installation_address' => 'Jl. Kebon Jeruk No. 8',
        'city' => 'Jakarta Barat',
        'district' => 'Kebon Jeruk',
        'status' => WifiRegistration::STATUS_PENDING,
        'commission_amount' => 50000,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.monitoring'))
        ->assertOk()
        ->assertSee('Monitoring Terpadu Sistem WiFi');

    Livewire::actingAs($this->admin)
        ->test(AdminMonitoring::class)
        ->call('openEditModal', $reg->id)
        ->set('editStatus', WifiRegistration::STATUS_INSTALLED)
        ->set('editAdminNotes', 'Pemasangan sukses selesai oleh tim teknisi.')
        ->set('editCommissionStatus', 'paid')
        ->call('saveRegistrationUpdate');

    $reg->refresh();
    expect($reg->status)->toBe(WifiRegistration::STATUS_INSTALLED);
    expect($reg->commission_status)->toBe('paid');
    expect($reg->installed_at)->not->toBeNull();
});

test('referrer dashboard displays only their own brought customers and stats', function (): void {
    $otherReferrer = User::create([
        'name' => 'Other Sales',
        'email' => 'other@sales.id',
        'role' => 'referrer',
        'referral_code' => 'OTHER123',
        'password' => bcrypt('password'),
    ]);

    // Budi's customer
    WifiRegistration::create([
        'registration_number' => 'REG-BUDI-01',
        'user_id' => $this->referrer->id,
        'wifi_package_id' => $this->pkgBasic->id,
        'customer_name' => 'Pelanggan Milik Budi',
        'customer_phone' => '081211111111',
        'installation_address' => 'Jl. Budi No. 1',
        'city' => 'Jakarta',
        'district' => 'Tebet',
        'status' => WifiRegistration::STATUS_INSTALLED,
        'commission_amount' => 50000,
    ]);

    // Other's customer
    WifiRegistration::create([
        'registration_number' => 'REG-OTHER-01',
        'user_id' => $otherReferrer->id,
        'wifi_package_id' => $this->pkgBasic->id,
        'customer_name' => 'Pelanggan Milik Orang Lain',
        'customer_phone' => '081222222222',
        'installation_address' => 'Jl. Lain No. 2',
        'city' => 'Jakarta',
        'district' => 'Kuningan',
        'status' => WifiRegistration::STATUS_PENDING,
    ]);

    Livewire::actingAs($this->referrer)
        ->test(ReferrerDashboard::class)
        ->assertSee('Pelanggan Milik Budi')
        ->assertDontSee('Pelanggan Milik Orang Lain')
        ->assertSee('BUDI2026');
});

test('login page displays afnalink branding, tagline and logo', function (): void {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Masuk AFNALINK')
        ->assertSee('teman ketika kamu Healing')
        ->assertSee('EMAIL ATAU NO. HP')
        ->assertSee('images/afnalink-logo.png');
});

test('user can login using phone number instead of email', function (): void {
    Livewire::test(Login::class)
        ->set('email', '081234567890')
        ->set('password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('referrer.dashboard'));

    expect(auth()->check())->toBeTrue();
    expect(auth()->user()->phone)->toBe('081234567890');
});

test('user can login using email address', function (): void {
    Livewire::test(Login::class)
        ->set('email', 'admin@wifinet.id')
        ->set('password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.monitoring'));

    expect(auth()->check())->toBeTrue();
    expect(auth()->user()->isAdmin())->toBeTrue();
});

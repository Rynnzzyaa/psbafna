<?php

use App\Livewire\DatabaseChecker;
use App\Services\DatabaseCheckerService;
use Livewire\Livewire;

test('database checker page returns a successful response', function () {
    $response = $this->get('/db-check');

    $response->assertOk();
    $response->assertSee('Pengecek Koneksi Database');
});

test('database checker service reports default sqlite connection', function () {
    $service = app(DatabaseCheckerService::class);
    $result = $service->checkDefaultConnection();

    expect($result)->toBeArray()
        ->and($result['connected'])->toBeTrue()
        ->and($result['driver'])->toBe('sqlite')
        ->and($result['tables_count'])->toBeGreaterThanOrEqual(1);
});

test('livewire database checker component can render and refresh default connection', function () {
    Livewire::test(DatabaseChecker::class)
        ->assertStatus(200)
        ->assertSee('Koneksi Default (.env)')
        ->call('refreshDefault')
        ->assertSet('defaultResult.connected', true);
});

test('livewire database checker can switch tabs', function () {
    Livewire::test(DatabaseChecker::class)
        ->call('setTab', 'custom')
        ->assertSet('activeTab', 'custom')
        ->call('setTab', 'tables')
        ->assertSet('activeTab', 'tables')
        ->call('setTab', 'environment')
        ->assertSet('activeTab', 'environment');
});

test('livewire database checker can run ping query', function () {
    $component = Livewire::test(DatabaseChecker::class)
        ->call('runPingQuery');

    expect($component->get('pingQueryResult'))->not->toBeNull()
        ->and($component->get('pingQueryLatency'))->not->toBeNull();
});

test('livewire database checker tests custom sqlite connection', function () {
    Livewire::test(DatabaseChecker::class)
        ->set('customDriver', 'sqlite')
        ->set('customDatabase', database_path('database.sqlite'))
        ->call('testCustom')
        ->assertSet('customResult.connected', true);
});

test('artisan db:check command runs successfully', function () {
    $this->artisan('db:check', ['--tables' => true])
        ->expectsOutputToContain('Koneksi database BERHASIL!')
        ->assertExitCode(0);
});

<?php

namespace App\Livewire;

use App\Services\DatabaseCheckerService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Throwable;

#[Title('Pengecek Koneksi Database')]
class DatabaseChecker extends Component
{
    public string $activeTab = 'default';

    public bool $autoRefresh = false;

    /** @var array<string, mixed> */
    public array $defaultResult = [];

    public string $customDriver = 'mysql';

    public string $customHost = '127.0.0.1';

    public string $customPort = '3306';

    public string $customDatabase = '';

    public string $customUsername = 'root';

    public string $customPassword = '';

    public int $customTimeout = 5;

    /** @var array<string, mixed>|null */
    public ?array $customResult = null;

    /** @var array<string, bool> */
    public array $availablePdoDrivers = [];

    /** @var array<string, mixed> */
    public array $environmentInfo = [];

    public ?string $pingQueryResult = null;

    public ?float $pingQueryLatency = null;

    public function mount(DatabaseCheckerService $service): void
    {
        $this->refreshDefault($service);
        $this->availablePdoDrivers = $service->getAvailablePdoDrivers();
        $this->loadEnvironmentInfo();
    }

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['default', 'custom', 'tables', 'environment'], true)) {
            $this->activeTab = $tab;
        }
    }

    public function toggleAutoRefresh(): void
    {
        $this->autoRefresh = ! $this->autoRefresh;
    }

    public function refreshDefault(DatabaseCheckerService $service): void
    {
        $this->defaultResult = $service->checkDefaultConnection();
    }

    public function updatedCustomDriver(string $value): void
    {
        $this->customPort = match (strtolower($value)) {
            'pgsql' => '5432',
            'sqlsrv' => '1433',
            'sqlite' => '',
            default => '3306',
        };

        $this->customResult = null;
    }

    public function testCustom(DatabaseCheckerService $service): void
    {
        if ($this->customDriver !== 'sqlite') {
            $this->validate([
                'customHost' => ['required', 'string'],
                'customPort' => ['required', 'numeric', 'min:1', 'max:65535'],
                'customUsername' => ['required', 'string'],
            ], [
                'customHost.required' => 'Host wajib diisi.',
                'customPort.required' => 'Port wajib diisi.',
                'customPort.numeric' => 'Port harus berupa angka valid.',
                'customUsername.required' => 'Username wajib diisi.',
            ]);
        } else {
            $this->validate([
                'customDatabase' => ['required', 'string'],
            ], [
                'customDatabase.required' => 'Path file database SQLite wajib diisi.',
            ]);
        }

        $this->customResult = $service->testCustomConnection([
            'driver' => $this->customDriver,
            'host' => $this->customHost,
            'port' => (int) $this->customPort,
            'database' => $this->customDatabase,
            'username' => $this->customUsername,
            'password' => $this->customPassword,
            'timeout' => $this->customTimeout,
        ]);
    }

    public function runPingQuery(): void
    {
        $startTime = microtime(true);

        try {
            $defaultName = config('database.default', 'sqlite');
            $driver = config("database.connections.{$defaultName}.driver", $defaultName);

            $query = match ($driver) {
                'sqlite' => 'SELECT datetime("now", "localtime") as current_time, sqlite_version() as version',
                'pgsql' => 'SELECT NOW() as current_time, version() as version',
                'sqlsrv' => 'SELECT GETDATE() as current_time, @@VERSION as version',
                default => 'SELECT NOW() as current_time, VERSION() as version',
            };

            $result = DB::connection($defaultName)->selectOne($query);
            $this->pingQueryLatency = round((microtime(true) - $startTime) * 1000, 2);

            $currentTime = $result->current_time ?? now()->format('Y-m-d H:i:s');
            $this->pingQueryResult = "Berhasil: {$query} (Waktu DB: {$currentTime})";
        } catch (Throwable $e) {
            $this->pingQueryLatency = round((microtime(true) - $startTime) * 1000, 2);
            $this->pingQueryResult = "Gagal: {$e->getMessage()}";
        }
    }

    protected function loadEnvironmentInfo(): void
    {
        $this->environmentInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'timezone' => config('app.timezone', 'Asia/Jakarta'),
            'os' => PHP_OS_FAMILY.' ('.php_uname('r').')',
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'CLI / Built-in Server',
            'memory_limit' => ini_get('memory_limit') ?: 'N/A',
            'max_execution_time' => ini_get('max_execution_time').' detik',
            'loaded_extensions_count' => count(get_loaded_extensions()),
        ];
    }

    public function render(): View
    {
        return view('livewire.database-checker');
    }
}

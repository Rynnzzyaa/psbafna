<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;
use Throwable;

class DatabaseCheckerService
{
    /**
     * Check the default database connection defined in the configuration.
     *
     * @return array<string, mixed>
     */
    public function checkDefaultConnection(): array
    {
        $defaultName = config('database.default', 'sqlite');
        $config = config("database.connections.{$defaultName}", []);
        $driver = $config['driver'] ?? $defaultName;
        $startTime = microtime(true);

        try {
            $connection = DB::connection($defaultName);
            $pdo = $connection->getPdo();
            $connection->selectOne('SELECT 1');
            $latency = round((microtime(true) - $startTime) * 1000, 2);

            $serverVersion = null;
            try {
                $serverVersion = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
            } catch (Throwable) {
                // Fallback for drivers that don't support ATTR_SERVER_VERSION directly
                $serverVersion = 'Unknown';
            }

            $tables = [];
            try {
                $tables = Schema::connection($defaultName)->getTableListing();
            } catch (Throwable) {
                $tables = [];
            }

            $migrationsCount = 0;
            $normalizedTables = array_map(fn ($tbl) => str_replace('main.', '', (string) $tbl), $tables);
            $hasMigrationsTable = in_array('migrations', $normalizedTables, true);
            if ($hasMigrationsTable) {
                try {
                    $migrationsCount = DB::connection($defaultName)->table('migrations')->count();
                } catch (Throwable) {
                    $migrationsCount = 0;
                }
            }

            $databaseName = $config['database'] ?? 'N/A';
            if ($driver === 'sqlite' && $databaseName !== ':memory:') {
                $dbPath = database_path(basename($databaseName));
                if (file_exists($databaseName)) {
                    $dbPath = $databaseName;
                }
                $size = file_exists($dbPath) ? $this->formatBytes((int) filesize($dbPath)) : 'N/A';
            } else {
                $size = 'N/A';
            }

            return [
                'status' => 'success',
                'connected' => true,
                'connection_name' => $defaultName,
                'driver' => $driver,
                'host' => $config['host'] ?? 'localhost',
                'port' => $config['port'] ?? ($driver === 'mysql' ? 3306 : ($driver === 'pgsql' ? 5432 : 'N/A')),
                'database' => $databaseName,
                'username' => $config['username'] ?? 'N/A',
                'server_version' => $serverVersion,
                'latency_ms' => $latency,
                'tables_count' => count($tables),
                'tables' => $tables,
                'has_migrations' => $hasMigrationsTable,
                'migrations_count' => $migrationsCount,
                'database_size' => $size,
                'tested_at' => now()->translatedFormat('d M Y, H:i:s').' '.(config('app.timezone') === 'Asia/Jakarta' ? 'WIB' : config('app.timezone', 'UTC')),
                'error' => null,
            ];
        } catch (Throwable $e) {
            $latency = round((microtime(true) - $startTime) * 1000, 2);

            return [
                'status' => 'error',
                'connected' => false,
                'connection_name' => $defaultName,
                'driver' => $driver,
                'host' => $config['host'] ?? 'localhost',
                'port' => $config['port'] ?? 'N/A',
                'database' => $config['database'] ?? 'N/A',
                'username' => $config['username'] ?? 'N/A',
                'server_version' => null,
                'latency_ms' => $latency,
                'tables_count' => 0,
                'tables' => [],
                'has_migrations' => false,
                'migrations_count' => 0,
                'database_size' => 'N/A',
                'tested_at' => now()->translatedFormat('d M Y, H:i:s').' '.(config('app.timezone') === 'Asia/Jakarta' ? 'WIB' : config('app.timezone', 'UTC')),
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'hint' => $this->getErrorHint($e->getMessage(), $driver),
                ],
            ];
        }
    }

    /**
     * Test a custom database connection dynamically using PDO.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function testCustomConnection(array $input): array
    {
        $driver = strtolower($input['driver'] ?? 'mysql');
        $host = $input['host'] ?? '127.0.0.1';
        $port = (int) ($input['port'] ?? ($driver === 'pgsql' ? 5432 : 3306));
        $database = $input['database'] ?? '';
        $username = $input['username'] ?? 'root';
        $password = $input['password'] ?? '';
        $timeout = (int) ($input['timeout'] ?? 5);

        $startTime = microtime(true);

        try {
            $dsn = '';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => $timeout,
            ];

            if ($driver === 'sqlite') {
                $dsn = "sqlite:{$database}";
                $pdo = new PDO($dsn, null, null, $options);
            } elseif ($driver === 'mysql') {
                $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
                if (! empty($database)) {
                    $dsn .= ";dbname={$database}";
                }
                $pdo = new PDO($dsn, $username, $password, $options);
            } elseif ($driver === 'pgsql') {
                $dsn = "pgsql:host={$host};port={$port}";
                if (! empty($database)) {
                    $dsn .= ";dbname={$database}";
                }
                $pdo = new PDO($dsn, $username, $password, $options);
            } elseif ($driver === 'sqlsrv') {
                $dsn = "sqlsrv:Server={$host},{$port}";
                if (! empty($database)) {
                    $dsn .= ";Database={$database}";
                }
                $pdo = new PDO($dsn, $username, $password, $options);
            } else {
                throw new Exception("Driver database [{$driver}] tidak didukung.");
            }

            $latency = round((microtime(true) - $startTime) * 1000, 2);
            $serverVersion = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);

            return [
                'status' => 'success',
                'connected' => true,
                'driver' => $driver,
                'host' => $host,
                'port' => $port,
                'database' => $database ?: '(Tanpa database spesifik)',
                'username' => $username,
                'server_version' => $serverVersion,
                'latency_ms' => $latency,
                'tested_at' => now()->translatedFormat('d M Y, H:i:s').' '.(config('app.timezone') === 'Asia/Jakarta' ? 'WIB' : config('app.timezone', 'UTC')),
                'error' => null,
            ];
        } catch (Throwable $e) {
            $latency = round((microtime(true) - $startTime) * 1000, 2);

            return [
                'status' => 'error',
                'connected' => false,
                'driver' => $driver,
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'username' => $username,
                'server_version' => null,
                'latency_ms' => $latency,
                'tested_at' => now()->translatedFormat('d M Y, H:i:s').' '.(config('app.timezone') === 'Asia/Jakarta' ? 'WIB' : config('app.timezone', 'UTC')),
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'hint' => $this->getErrorHint($e->getMessage(), $driver),
                ],
            ];
        }
    }

    /**
     * Get information about installed PDO drivers in PHP environment.
     *
     * @return array<string, bool>
     */
    public function getAvailablePdoDrivers(): array
    {
        $available = PDO::getAvailableDrivers();
        $supported = ['mysql', 'sqlite', 'pgsql', 'sqlsrv'];

        $result = [];
        foreach ($supported as $driver) {
            $result[$driver] = in_array($driver, $available, true);
        }

        return $result;
    }

    /**
     * Get diagnostic hint for a given database error message.
     */
    protected function getErrorHint(string $errorMessage, string $driver): string
    {
        $lower = strtolower($errorMessage);

        if (str_contains($lower, 'access denied')) {
            return 'Username atau password database salah. Periksa kembali kredensial database Anda.';
        }

        if (str_contains($lower, 'unknown database') || str_contains($lower, 'does not exist')) {
            return 'Nama database tidak ditemukan di server. Pastikan database sudah dibuat terlebih dahulu.';
        }

        if (str_contains($lower, 'connection refused') || str_contains($lower, 'could not connect to server') || str_contains($lower, 'target machine actively refused')) {
            return "Server database tidak sedang berjalan atau port salah. Pastikan service {$driver} sudah aktif (misal via panel Laragon).";
        }

        if (str_contains($lower, 'could not find driver')) {
            return "Ekstensi PHP 'pdo_{$driver}' belum diaktifkan di file php.ini.";
        }

        if (str_contains($lower, 'timed out') || str_contains($lower, 'timeout')) {
            return 'Koneksi melebihi batas waktu (timeout). Periksa apakah Host dan Port dapat dijangkau serta firewall tidak memblokir.';
        }

        return 'Periksa kembali parameter koneksi database Anda atau periksa log database.';
    }

    /**
     * Format bytes into human readable format.
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2).' '.$units[$pow];
    }
}

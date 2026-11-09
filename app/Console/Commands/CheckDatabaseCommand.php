<?php

namespace App\Console\Commands;

use App\Services\DatabaseCheckerService;
use Illuminate\Console\Command;

class CheckDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check {--tables : Tampilkan daftar tabel yang ada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengecek status dan performa koneksi database';

    /**
     * Execute the console command.
     */
    public function handle(DatabaseCheckerService $service): int
    {
        $this->info('🔍 Sedang memeriksa koneksi database...');
        $this->newLine();

        $result = $service->checkDefaultConnection();

        if ($result['connected']) {
            $this->components->info('Koneksi database BERHASIL!');

            $this->table(
                ['Parameter', 'Nilai'],
                [
                    ['Koneksi Default', $result['connection_name']],
                    ['Driver', $result['driver']],
                    ['Host', $result['host']],
                    ['Port', (string) $result['port']],
                    ['Database', $result['database']],
                    ['Username', $result['username']],
                    ['Versi Server', (string) $result['server_version']],
                    ['Latency / Response Time', $result['latency_ms'].' ms'],
                    ['Jumlah Tabel', (string) $result['tables_count']],
                    ['Status Migrasi', $result['has_migrations'] ? "{$result['migrations_count']} migrasi tercatat" : 'Tabel migrations belum ada'],
                    ['Ukuran Database', (string) $result['database_size']],
                    ['Waktu Pengujian', $result['tested_at']],
                ]
            );

            if ($this->option('tables') && ! empty($result['tables'])) {
                $this->newLine();
                $this->info('📋 Daftar Tabel:');
                $tableRows = array_map(fn ($tbl, $idx) => [$idx + 1, $tbl], $result['tables'], array_keys($result['tables']));
                $this->table(['No', 'Nama Tabel'], $tableRows);
            }

            return self::SUCCESS;
        }

        $this->components->error('Koneksi database GAGAL!');

        $this->table(
            ['Parameter', 'Nilai'],
            [
                ['Koneksi Default', $result['connection_name']],
                ['Driver', $result['driver']],
                ['Host', $result['host']],
                ['Port', (string) $result['port']],
                ['Database', $result['database']],
                ['Username', $result['username']],
                ['Latency', $result['latency_ms'].' ms'],
                ['Pesan Error', $result['error']['message'] ?? 'Unknown error'],
            ]
        );

        if (! empty($result['error']['hint'])) {
            $this->newLine();
            $this->components->warn('💡 Saran Perbaikan: '.$result['error']['hint']);
        }

        return self::FAILURE;
    }
}

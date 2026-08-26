<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PackageSeeder::class,
            NetworkNodeSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,

            // TicketSeeder & InvoiceSeeder sengaja tidak dipanggil:
            // data transaksi dimulai bersih, invoice dibuat lewat
            // panel Admin -> Generate Invoice Bulanan (FR-BIL-01).
        ]);
    }
}

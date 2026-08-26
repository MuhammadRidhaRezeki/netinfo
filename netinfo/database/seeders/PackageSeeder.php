<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            ['name' => 'Home 10 Mbps', 'speed_mbps' => 10, 'price' => 150000, 'description' => 'Paket hemat untuk kebutuhan dasar rumah tangga.'],
            ['name' => 'Home 20 Mbps', 'speed_mbps' => 20, 'price' => 250000, 'description' => 'Ideal untuk streaming HD dan work from home.'],
            ['name' => 'Home 50 Mbps', 'speed_mbps' => 50, 'price' => 450000, 'description' => 'Untuk keluarga dengan banyak perangkat & gaming.'],
            ['name' => 'Business 100 Mbps', 'speed_mbps' => 100, 'price' => 750000, 'description' => 'Dedicated support + IP publik untuk usaha.'],
        ];

        foreach ($packages as $p) {
            Package::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}

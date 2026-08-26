<?php

namespace Database\Seeders;

use App\Models\NetworkNode;
use Illuminate\Database\Seeder;

class NetworkNodeSeeder extends Seeder
{
    public function run(): void
    {
        $nodes = [
            ['name' => 'ODP-BNA-01', 'location_area' => 'Jl. Tugu Adipura, Banda Aceh', 'ip_address' => '10.10.1.1', 'status' => 'active'],
            ['name' => 'ODP-LSM-01', 'location_area' => 'Jl. Sudirman, Lhokseumawe', 'ip_address' => '10.10.1.2', 'status' => 'active'],
            ['name' => 'ODP-ATU-01', 'location_area' => 'Jl. Merdeka, Lhoksukon, Aceh Utara', 'ip_address' => '10.10.1.3', 'status' => 'maintenance'],
            ['name' => 'ODP-BIR-01', 'location_area' => 'Perum Kota Bireuen Blok C', 'ip_address' => '10.10.1.4', 'status' => 'active'],
            ['name' => 'ODP-PDE-01', 'area_dummy' => null, 'location_area' => 'Jl. Tgk. Daud Beureueh, Sigli, Pidie', 'ip_address' => null, 'status' => 'down'],
        ];

        foreach ($nodes as $n) {
            unset($n['area_dummy']);
            NetworkNode::updateOrCreate(['name' => $n['name']], $n);
        }
    }
}

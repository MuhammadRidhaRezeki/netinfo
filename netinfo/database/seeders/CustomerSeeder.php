<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\NetworkNode;
use App\Models\Package;
use App\Support\Codes;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $packages = Package::orderBy('price')->get()->values();
        $nodes = NetworkNode::orderBy('id')->get()->values();

        // Node yang lokasinya beririsan dengan kota pelanggan diprioritaskan.
        $nodeByCity = [
            'Banda Aceh'  => 'ODP-BNA-01',
            'Lhokseumawe' => 'ODP-LSM-01',
            'Aceh Utara'  => 'ODP-ATU-01',
            'Bireuen'     => 'ODP-BIR-01',
            'Pidie'       => 'ODP-PDE-01',
        ];

        $fallbackIndex = 0;

        foreach (UserSeeder::customers() as $i => $c) {
            $user = \App\Models\User::where('email', $c['email'])->firstOrFail();

            $cityName = trim(explode(',', $c['city'])[0]);
            $node = isset($nodeByCity[$cityName])
                ? $nodes->firstWhere('name', $nodeByCity[$cityName])
                : $nodes[$fallbackIndex++ % $nodes->count()];

            Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'package_id'        => $packages[$i % $packages->count()]->id,
                    'node_id'           => $node->id,
                    'customer_code'     => Codes::forCustomer(),
                    'address'           => $c['city'],
                    'phone'             => $c['phone'],
                    'installation_date' => CarbonImmutable::parse('2025-11-05')->addDays($i * 10)->toDateString(),
                    'status'            => 'active',
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admin NOC / Billing
            ['name' => 'Muhammad Ridha Rezeki', 'email' => 'muhammadridharezeki@gmail.com', 'role' => 'admin'],
            ['name' => 'Rausyanul Fikri', 'email' => 'rosan@gmail.com', 'role' => 'admin'],

            // Teknisi Lapangan
            ['name' => 'Nabil Gathfan Putra Mulyana', 'email' => 'gatpan@gmail.com', 'role' => 'technician'],
            ['name' => 'Ikhsan Salsabily', 'email' => 'isan@gmail.com', 'role' => 'technician'],
        ];

        foreach ($this->customers() as $c) {
            $users[] = ['name' => $c['name'], 'email' => $c['email'], 'role' => 'customer'];
        }

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                $u + ['password' => 'password']
            );
        }
    }

    public static function customers(): array
    {
        return [
            ['name' => 'MUHAMMAD DHYAUL ATHA', 'email' => 'muhammadhyaulatha@gmail.com', 'phone' => '0812-0000-0001', 'city' => 'Banda Aceh, Aceh'],
            ['name' => 'RAJA MAULIDINSYAH PUTRA', 'email' => 'rajamaulidinsyahputra@gmail.com', 'phone' => '0812-0000-0002', 'city' => 'Lhokseumawe, Aceh'],
            ['name' => 'RIVAN GHAEZKA ATTALLAH', 'email' => 'rivanghaezkaattallah@gmail.com', 'phone' => '0812-0000-0003', 'city' => 'Aceh Utara, Aceh'],
            ['name' => 'T. Muhammad Irsan Trifiery', 'email' => 'tmuhammadirsantrifiery@gmail.com', 'phone' => '0812-0000-0004', 'city' => 'Bireuen, Aceh'],
            ['name' => 'MUHAMMAD ALIF ARRAYAN', 'email' => 'muhammadalifarrayan@gmail.com', 'phone' => '0812-0000-0005', 'city' => 'Pidie, Aceh'],
            ['name' => 'NAILIS SAPUTRI', 'email' => 'nailissaputri@gmail.com', 'phone' => '0812-0000-0006', 'city' => 'Aceh Besar, Aceh'],
            ['name' => 'Naiza Fitri', 'email' => 'naizafitri@gmail.com', 'phone' => '0812-0000-0007', 'city' => 'Langsa, Aceh'],
            ['name' => 'Nasywa Ariqa Ridha', 'email' => 'nasywaariqaridha@gmail.com', 'phone' => '0812-0000-0008', 'city' => 'Aceh Tamiang, Aceh'],
            ['name' => 'NATIA HAYANI', 'email' => 'natiahayani@gmail.com', 'phone' => '0812-0000-0009', 'city' => 'Pidie Jaya, Aceh'],
            ['name' => 'NAYLA MUTIA SILVIA DINA', 'email' => 'naylamutiasilviadina@gmail.com', 'phone' => '0812-0000-0010', 'city' => 'Bener Meriah, Aceh'],
            ['name' => 'Putri Balqis', 'email' => 'putribalqis@gmail.com', 'phone' => '0812-0000-0011', 'city' => 'Aceh Tengah, Aceh'],
            ['name' => 'SAFIRA MUNITA', 'email' => 'safiramunita@gmail.com', 'phone' => '0812-0000-0012', 'city' => 'Aceh Barat, Aceh'],
            ['name' => 'SALSABILA', 'email' => 'salsabila@gmail.com', 'phone' => '0812-0000-0013', 'city' => 'Aceh Selatan, Aceh'],
            ['name' => 'Shaista Ifra Zia Rosant', 'email' => 'shaistaifraziarosant@gmail.com', 'phone' => '0812-0000-0014', 'city' => 'Aceh Singkil, Aceh'],
            ['name' => 'Zakiatunnisa', 'email' => 'zakiatunnisa@gmail.com', 'phone' => '0812-0000-0015', 'city' => 'Aceh Barat Daya, Aceh'],
            ['name' => 'ZUHRATUL FAZLA', 'email' => 'zuhratulfazla@gmail.com', 'phone' => '0812-0000-0016', 'city' => 'Aceh Jaya, Aceh'],
            ['name' => 'Ammar', 'email' => 'ammar@gmail.com', 'phone' => '0812-0000-0017', 'city' => 'Sabang, Aceh'],
            ['name' => 'Nikmal Wakil Aizza', 'email' => 'nikmalwakilaizza@gmail.com', 'phone' => '0812-0000-0018', 'city' => 'Simeulue, Aceh'],
            ['name' => 'ADHA GUSTI HARMADHAN', 'email' => 'adhagustiharmadhan@gmail.com', 'phone' => '0812-0000-0019', 'city' => 'Aceh Tenggara, Aceh'],
            ['name' => 'AFDAL FAHRIZA', 'email' => 'afdalfahriza@gmail.com', 'phone' => '0812-0000-0020', 'city' => 'Aceh Timur, Aceh'],
            ["name" => "A'LLIEYA MAYSARAH", 'email' => 'allieyamaysarah@gmail.com', 'phone' => '0812-0000-0021', 'city' => 'Aceh Barat, Aceh'],
            ['name' => 'ARINI SAFITRI', 'email' => 'arinisafitri@gmail.com', 'phone' => '0812-0000-0022', 'city' => 'Lhokseumawe, Aceh'],
            ['name' => 'AYU AMELIA', 'email' => 'ayuamelia@gmail.com', 'phone' => '0812-0000-0023', 'city' => 'Banda Aceh, Aceh'],
        ];
    }
}

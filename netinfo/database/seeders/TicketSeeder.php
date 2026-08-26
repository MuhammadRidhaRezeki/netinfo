<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::with('user')->get()
            ->keyBy(fn ($c) => $c->user->email);
        $admins = User::where('role', 'admin')->orderBy('id')->get();
        $techs = User::where('role', 'technician')->orderBy('id')->get();

        [$admin1, $admin2] = [$admins[0], $admins[1]];
        [$nabil, $ikhsan] = [$techs[0], $techs[1]];

        $dhyaul = $customers['muhammadhyaulatha@gmail.com'];
        $raja = $customers['rajamaulidinsyahputra@gmail.com'];
        $rivan = $customers['rivanghaezkaattallah@gmail.com'];
        $naiza = $customers['naizafitri@gmail.com'];

        $tickets = [
            [
                'customer' => $dhyaul, 'technician' => $nabil, 'code_date' => '20260822', 'seq' => 147,
                'issue_title' => 'LOS Merah / Redaman Tinggi',
                'description' => 'Internet putus total sejak pukul 06.30. Lampu LOS pada ONT menyala merah terus-menerus, lampu PON mati.',
                'priority' => 'high', 'status' => 'in_progress', 'created_at' => '2026-08-22 08:45:00',
                'histories' => [
                    ['user' => $dhyaul->user, 'action_type' => 'created', 'note' => 'Melaporkan gangguan: internet putus total sejak 06.30 pagi.', 'at' => '2026-08-22 08:45:00'],
                    ['user' => $admin1, 'action_type' => 'assigned', 'note' => 'Ditugaskan kepada Nabil Gathfan Putra Mulyana.', 'at' => '2026-08-22 09:10:00'],
                    ['user' => $nabil, 'action_type' => 'status_changed', 'note' => 'Status diubah menjadi In Progress. Survey lokasi dimulai.', 'at' => '2026-08-22 09:35:00'],
                    ['user' => $nabil, 'action_type' => 'note_added', 'note' => 'Redaman terukur -28,4 dBm. Ditemukan konektor di ODC korosi, penggantian pigtail dilakukan.', 'at' => '2026-08-22 11:20:00'],
                ],
            ],
            [
                'customer' => $raja, 'technician' => null, 'code_date' => '20260823', 'seq' => 148,
                'issue_title' => 'Internet Putus Total Sejak Pagi',
                'description' => 'Seluruh perangkat tidak bisa konek ke internet. Sudah restart ONT berkali-kali tetap tidak ada perubahan.',
                'priority' => 'high', 'status' => 'open', 'created_at' => '2026-08-23 08:02:00',
                'histories' => [
                    ['user' => $raja->user, 'action_type' => 'created', 'note' => 'Membuat laporan gangguan baru.', 'at' => '2026-08-23 08:02:00'],
                ],
            ],
            [
                'customer' => $raja, 'technician' => $ikhsan, 'code_date' => '20260821', 'seq' => 146,
                'issue_title' => 'Kabel Drop Tergulung Pohon Tumbang',
                'description' => 'Kabel internet tertarik karena pohon tumbang diterjang angin kencang semalam.',
                'priority' => 'medium', 'status' => 'in_progress', 'created_at' => '2026-08-21 14:47:00',
                'histories' => [
                    ['user' => $raja->user, 'action_type' => 'created', 'note' => 'Melaporkan kabel drop terputus.', 'at' => '2026-08-21 14:47:00'],
                    ['user' => $admin2, 'action_type' => 'assigned', 'note' => 'Ditugaskan kepada Ikhsan Salsabily.', 'at' => '2026-08-21 15:30:00'],
                    ['user' => $ikhsan, 'action_type' => 'status_changed', 'note' => 'Pengecekan jalur udara dimulai.', 'at' => '2026-08-21 16:05:00'],
                ],
            ],
            [
                'customer' => $naiza, 'technician' => $ikhsan, 'code_date' => '20260820', 'seq' => 145,
                'issue_title' => 'WiFi Sering Terputus Sendiri',
                'description' => 'Koneksi stabil di siang hari namun sering disconnect tiap 10 menit pada malam hari.',
                'priority' => 'medium', 'status' => 'resolved', 'created_at' => '2026-08-20 10:30:00', 'resolved_at' => '2026-08-20 15:00:00',
                'histories' => [
                    ['user' => $naiza->user, 'action_type' => 'created', 'note' => 'Melaporkan WiFi sering terputus.', 'at' => '2026-08-20 10:30:00'],
                    ['user' => $admin1, 'action_type' => 'assigned', 'note' => 'Ditugaskan kepada Ikhsan Salsabily.', 'at' => '2026-08-20 11:00:00'],
                    ['user' => $ikhsan, 'action_type' => 'status_changed', 'note' => 'Pengecekan channel & interferensi dilakukan.', 'at' => '2026-08-20 13:00:00'],
                    ['user' => $ikhsan, 'action_type' => 'status_changed', 'note' => 'Ganti channel WiFi & firmware router. Koneksi stabil 2 jam pengamatan.', 'at' => '2026-08-20 15:00:00'],
                ],
            ],
            [
                'customer' => $rivan, 'technician' => $nabil, 'code_date' => '20260818', 'seq' => 144,
                'issue_title' => 'Modem Mati Total (Adaptor Rusak)',
                'description' => 'Modem tidak meny sama sekali setelah badai listrik semalam. Indikator power mati.',
                'priority' => 'low', 'status' => 'resolved', 'created_at' => '2026-08-18 09:22:00', 'resolved_at' => '2026-08-18 13:40:00',
                'histories' => [
                    ['user' => $rivan->user, 'action_type' => 'created', 'note' => 'Melaporkan modem mati total.', 'at' => '2026-08-18 09:22:00'],
                    ['user' => $admin1, 'action_type' => 'assigned', 'note' => 'Ditugaskan kepada Nabil Gathfan Putra Mulyana.', 'at' => '2026-08-18 09:50:00'],
                    ['user' => $nabil, 'action_type' => 'status_changed', 'note' => 'Survey unit & penggantian adaptor 12V 1A.', 'at' => '2026-08-18 11:00:00'],
                    ['user' => $nabil, 'action_type' => 'status_changed', 'note' => 'Adaptor diganti, ONT normal kembali. Kendala selesai.', 'at' => '2026-08-18 13:40:00'],
                ],
            ],
            [
                'customer' => $dhyaul, 'technician' => null, 'code_date' => '20260824', 'seq' => 149,
                'issue_title' => 'Streaming Buffering Setiap Malam',
                'description' => 'Kecepatan turun drastis pukul 19.00-23.00, YouTube dan Netflix buffering terus padahal siang lancar.',
                'priority' => 'medium', 'status' => 'open', 'created_at' => '2026-08-24 09:40:00',
                'histories' => [
                    ['user' => $dhyaul->user, 'action_type' => 'created', 'note' => 'Melaporkan penurunan kecepatan malam hari.', 'at' => '2026-08-24 09:40:00'],
                ],
            ],
            [
                'customer' => $naiza, 'technician' => $ikhsan, 'code_date' => '20260710', 'seq' => 138,
                'issue_title' => 'Ganti Perangkat ONT Lama',
                'description' => 'Permintaan upgrade perangkat ONT lama tipe lama yang sudah tidak mendukung dual band.',
                'priority' => 'low', 'status' => 'closed', 'created_at' => '2026-07-10 11:20:00', 'resolved_at' => '2026-07-12 10:00:00',
                'histories' => [
                    ['user' => $naiza->user, 'action_type' => 'created', 'note' => 'Permintaan penggantian ONT.', 'at' => '2026-07-10 11:20:00'],
                    ['user' => $admin2, 'action_type' => 'assigned', 'note' => 'Ditugaskan kepada Ikhsan Salsabily.', 'at' => '2026-07-11 09:00:00'],
                    ['user' => $ikhsan, 'action_type' => 'status_changed', 'note' => 'ONT baru terpasang & terkonfigurasi.', 'at' => '2026-07-12 10:00:00'],
                    ['user' => $admin1, 'action_type' => 'status_changed', 'note' => 'Tiket ditutup setelah konfirmasi pelanggan.', 'at' => '2026-07-13 08:30:00'],
                ],
            ],
        ];

        foreach ($tickets as $t) {
            /** @var Ticket $ticket */
            $ticket = Ticket::updateOrCreate(
                ['ticket_code' => sprintf('TICK-%s-%04d', $t['code_date'], $t['seq'])],
                [
                    'customer_id' => $t['customer']->id,
                    'technician_id' => $t['technician']?->id,
                    'issue_title' => $t['issue_title'],
                    'description' => $t['description'],
                    'priority' => $t['priority'],
                    'status' => $t['status'],
                    'resolved_at' => $t['resolved_at'] ?? null,
                    'created_at' => $t['created_at'],
                    'updated_at' => $t['resolved_at'] ?? $t['created_at'],
                ]
            );

            foreach ($t['histories'] as $h) {
                $exists = $ticket->histories()
                    ->where('user_id', $h['user']->id)
                    ->where('action_type', $h['action_type'])
                    ->where('created_at', $h['at'])
                    ->exists();

                if (! $exists) {
                    $ticket->histories()->create([
                        'user_id' => $h['user']->id,
                        'action_type' => $h['action_type'],
                        'note' => $h['note'],
                        'created_at' => $h['at'],
                        'updated_at' => $h['at'],
                    ]);
                }
            }
        }
    }
}

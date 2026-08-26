<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::with('user', 'package')->get()
            ->keyBy(fn ($c) => $c->user->email);

        $dhyaul = $customers['muhammadhyaulatha@gmail.com'];
        $raja = $customers['rajamaulidinsyahputra@gmail.com'];
        $rivan = $customers['rivanghaezkaattallah@gmail.com'];
        $naiza = $customers['naizafitri@gmail.com'];

        $this->ensureProofPlaceholder();

        $rows = [
            [$dhyaul, '2026-06', '2026-06-25', 'paid', '2026-06-21 10:15:00', null],
            [$dhyaul, '2026-07', '2026-07-25', 'paid', '2026-07-19 20:40:00', null],
            [$dhyaul, '2026-08', '2026-08-25', 'unpaid', null, 'proofs/demo-bukti-transfer-dhyaul.png'],
            [$raja, '2026-07', '2026-07-25', 'paid', '2026-07-22 09:05:00', null],
            [$raja, '2026-08', '2026-08-25', 'unpaid', null, null],
            [$rivan, '2026-06', '2026-06-25', 'paid', '2026-06-24 14:30:00', null],
            [$rivan, '2026-07', '2026-07-25', 'paid', '2026-07-24 16:12:00', null],
            [$rivan, '2026-08', '2026-08-10', 'unpaid', null, null],
            [$naiza, '2026-07', '2026-07-25', 'paid', '2026-07-18 08:55:00', null],
            [$naiza, '2026-08', '2026-08-25', 'paid', '2026-08-14 11:47:00', null],
        ];

        foreach ($rows as [$customer, $month, $due, $status, $paidAt, $proof]) {
            $this->seq[$month] = ($this->seq[$month] ?? 0) + 1;

            Invoice::updateOrCreate(
                ['customer_id' => $customer->id, 'billing_month' => $month],
                [
                    'invoice_code' => sprintf('INV-%s-%04d', str_replace('-', '', $month), $this->seq[$month]),
                    'amount' => $customer->package->price,
                    'due_date' => $due,
                    'payment_status' => $status,
                    'payment_date' => $paidAt,
                    'payment_proof' => $proof,
                    'created_at' => $month . '-01 06:00:00',
                ]
            );
        }
    }

    private array $seq = [];

    private function seq(string $month): int
    {
        return $this->seq[$month] ?? 1;
    }

    private function ensureProofPlaceholder(): void
    {
        if (Storage::disk('public')->exists('proofs/demo-bukti-transfer-dhyaul.png')) {
            return;
        }

        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAIAAAD/gAIDAAAAaElEQVR4nO3QMQEAAAjDMMC/5+EBviIhacA//wEAABw6BAAAOHQIAABy6BAAAOHQIQAAwKFDAACAQ4cAACCHDgEAQA4dAgCAHDoEAAA4dAgAAHLoEAAAYP8CdfAAe2fYPV0AAAAASUVORK5CYII=');
        Storage::disk('public')->put('proofs/demo-bukti-transfer-dhyaul.png', $png);
    }
}

<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class Codes
{
    public static function forCustomer(): string
    {
        $prefix = 'CUST-' . now()->format('Ym') . '-';

        return self::next('customers', 'customer_code', $prefix);
    }

    public static function forTicket(): string
    {
        $prefix = 'TICK-' . now()->format('Ymd') . '-';

        return self::next('tickets', 'ticket_code', $prefix);
    }

    public static function forInvoice(string $billingMonth): string
    {
        $prefix = 'INV-' . str_replace('-', '', $billingMonth) . '-';

        return self::next('invoices', 'invoice_code', $prefix);
    }

    private static function next(string $table, string $column, string $prefix): string
    {
        $last = DB::table($table)
            ->where($column, 'like', $prefix . '%')
            ->orderByDesc($column)
            ->value($column);

        $next = ((int) substr((string) $last, strlen($prefix))) + 1;

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}

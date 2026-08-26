<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_code', 30)->unique();
            $table->string('billing_month', 7)->index();
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->enum('payment_status', ['unpaid', 'paid', 'cancelled'])->default('unpaid')->index();
            $table->timestamp('payment_date')->nullable();
            $table->string('payment_proof')->nullable();
            $table->timestamps();

            $table->unique(['customer_id', 'billing_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

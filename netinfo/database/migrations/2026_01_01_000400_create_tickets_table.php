<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ticket_code', 30)->unique();
            $table->string('issue_title');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->index();
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open')->index();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'technician_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

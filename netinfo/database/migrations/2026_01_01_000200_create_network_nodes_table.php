<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('network_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('location_area');
            $table->string('ip_address', 45)->nullable();
            $table->enum('status', ['active', 'maintenance', 'down'])->default('active')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('network_nodes');
    }
};

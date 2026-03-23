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
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('buyer_name');
            $table->string('seller_name');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('meter_price', 15, 2)->nullable();
            $table->date('offer_date')->nullable();
            $table->date('sale_date')->nullable();
            $table->decimal('company_commission_from_buyer', 15, 2)->nullable();
            $table->decimal('company_commission_from_seller', 15, 2)->nullable();
            $table->decimal('employee_commission_value', 15, 2)->nullable();
            $table->decimal('employee_commission_percent', 5, 2)->nullable();
            $table->enum('commission_settlement', ['now', 'after_target'])->default('after_target');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

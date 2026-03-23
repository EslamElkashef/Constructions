<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();

            // ربط الموظف
            $table->foreignId('employee_id')
                ->constrained()
                ->onDelete('cascade');

            // تفاصيل المرتب
            $table->string('month'); // مثال: "October"
            $table->integer('year'); // مثال: 2025

            $table->decimal('basic_salary', 10, 2);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->string('allowance_reason')->nullable(); // سبب الزيادة إن وجد
            $table->decimal('deductions', 10, 2)->default(0);
            $table->string('deduction_reason')->nullable(); // سبب الخصم إن وجد

            $table->decimal('net_salary', 10, 2);
            $table->date('payment_date')->nullable();

            $table->string('status')->default('Pending'); // Pending / Paid

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};

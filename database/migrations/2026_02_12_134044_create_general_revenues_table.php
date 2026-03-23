<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_revenues', function (Blueprint $table) {
            $table->id();

            $table->string('title'); // اسم الإيراد
            $table->string('received_from')->nullable(); // مستلم من
            $table->decimal('amount', 15, 2);

            $table->string('category'); // نوع الإيراد

            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('payment_method', ['cash', 'bank', 'wallet']);

            $table->string('reference_number')->nullable();
            $table->date('date');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('category');
            $table->index('date');
            $table->index('payment_method');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_revenues');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('salesperson_id')->nullable();
            $table->unsignedBigInteger('lead_id')->nullable(); // العميل

            $table->decimal('price', 12, 2);
            $table->decimal('paid', 12, 2)->default(0);
            $table->decimal('remaining', 12, 2)->default(0);

            $table->date('sale_date');
            $table->date('due_date')->nullable();

            $table->enum('status', [
                'paid', 'partially_paid', 'overdue',
            ])->default('partially_paid');

            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('salesperson_id')->references('id')->on('salespersons');
            $table->foreign('lead_id')->references('id')->on('leads');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

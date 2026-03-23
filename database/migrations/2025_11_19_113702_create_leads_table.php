<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->enum('source', [
                'facebook', 'instagram', 'website', 'whatsapp',
                'tiktok', 'referral', 'broker', 'walk_in',
            ])->nullable();

            $table->unsignedBigInteger('salesperson_id')->nullable();

            $table->enum('stage', [
                'new', 'contacted', 'qualified', 'visit_scheduled',
                'offer_sent', 'booked', 'sold', 'closed_lost',
            ])->default('new');

            $table->timestamps();

            $table->foreign('salesperson_id')->references('id')->on('salespersons');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

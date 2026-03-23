<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city')->nullable();
            $table->integer('total_units')->nullable();
            $table->decimal('budget', 14, 2)->nullable();
            $table->integer('progress')->default(0); // %
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects_reports');
    }
};

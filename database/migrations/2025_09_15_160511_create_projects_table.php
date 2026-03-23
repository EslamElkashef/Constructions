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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ← الربط بالمستخدم
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('priority', ['High', 'Medium', 'Low']);
            $table->enum('status', ['Inprogress', 'Completed']);
            $table->date('deadline')->nullable();
            $table->enum('privacy', ['Private', 'Team', 'Public'])->default('Private');
            $table->string('categories')->nullable();
            $table->string('skills')->nullable();
            $table->unsignedBigInteger('team_lead_id')->nullable();
            $table->text('attached_files')->nullable();
            $table->decimal('budget', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('project_comments', function (Blueprint $table) {
            if (Schema::hasColumn('project_comments', 'comment')) {
                $table->dropColumn('comment');
            }
        });
    }

    public function down()
    {
        Schema::table('project_comments', function (Blueprint $table) {
            $table->text('comment')->nullable();
        });
    }
};

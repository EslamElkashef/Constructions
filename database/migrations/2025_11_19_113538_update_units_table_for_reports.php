<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {

            // if (! Schema::hasColumn('units', 'project_id')) {
            //     $table->unsignedBigInteger('project_id')->nullable()->after('id');
            // }

            if (! Schema::hasColumn('units', 'city')) {
                $table->string('city')->nullable()->after('id');
            }

            // if (! Schema::hasColumn('units', 'type')) {
            //     $table->string('type')->nullable()->after('city'); // شقة – فيلا – محل...
            // }

            // if (! Schema::hasColumn('units', 'area')) {
            //     $table->integer('area')->nullable()->after('type');
            // }

            // if (! Schema::hasColumn('units', 'price')) {
            //     $table->decimal('price', 12, 2)->nullable()->after('area');
            // }

            if (! Schema::hasColumn('units', 'status')) {
                $table->enum('status', ['available', 'sold', 'reserved'])
                    ->default('available')
                    ->after('price');
            }

            if (! Schema::hasColumn('units', 'sold_at')) {
                $table->date('sold_at')->nullable()->after('status');
            }

            // if (! Schema::hasColumn('units', 'company_share')) {
            //     $table->decimal('company_share', 5, 2)
            //         ->nullable()
            //         ->comment('percentage %')
            //         ->after('sold_at');
            // }
        });
    }

    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'project_id',
                'city',
                'type',
                'area',
                'price',
                'status',
                'sold_at',
                'company_share',
            ]);
        });
    }
};

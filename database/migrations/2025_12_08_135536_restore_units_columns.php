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
        Schema::table('units', function (Blueprint $table) {

            if (! Schema::hasColumn('units', 'type')) {
                $table->string('type')->nullable()->after('city'); // شقة – فيلا – محل...
            }

            if (! Schema::hasColumn('units', 'area')) {
                $table->integer('area')->nullable()->after('type');
            }

            if (! Schema::hasColumn('units', 'price')) {
                $table->decimal('price', 12, 2)->nullable()->after('area');
            }

            if (! Schema::hasColumn('units', 'company_share')) {
                $table->decimal('company_share', 5, 2)
                    ->nullable()
                    ->comment('percentage %')
                    ->after('sold_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'area',
                'price',
                'company_share',
            ]);
        });
    }
};

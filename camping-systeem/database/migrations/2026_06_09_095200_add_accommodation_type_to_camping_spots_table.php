<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camping_spots', function (Blueprint $table) {
            $table->string('accommodation_type')
                ->default('camping_pitch')
                ->after('price_per_night');
        });
    }

    public function down(): void
    {
        Schema::table('camping_spots', function (Blueprint $table) {
            $table->dropColumn('accommodation_type');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camping_spot_id')->constrained()->cascadeOnDelete();
            $table->string('guest_name');
            $table->string('guest_email')->index();
            $table->string('guest_phone', 50)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('party_size');
            $table->string('status')->default('confirmed')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['camping_spot_id', 'start_date', 'end_date']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

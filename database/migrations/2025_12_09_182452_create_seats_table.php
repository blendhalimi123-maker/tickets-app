<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixture_id');
            $table->unsignedBigInteger('match_id')->nullable();
            $table->string('seat_identifier')->nullable();
            $table->string('seat_info')->nullable();
            $table->string('stand')->nullable();
            $table->string('row');
            $table->integer('number');
            $table->string('category')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->boolean('is_booked')->default(false);
            $table->timestamps();

            $table->unique(['fixture_id', 'row', 'number']);
            $table->unique(['fixture_id', 'seat_identifier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
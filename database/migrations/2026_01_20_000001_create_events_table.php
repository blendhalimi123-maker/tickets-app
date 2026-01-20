<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');

            $table->decimal('east_price', 8, 2)->default(0);
            $table->decimal('west_price', 8, 2)->default(0);
            $table->decimal('west_vip_price', 8, 2)->default(0);
            $table->decimal('north_price', 8, 2)->default(0);
            $table->decimal('south_price', 8, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};



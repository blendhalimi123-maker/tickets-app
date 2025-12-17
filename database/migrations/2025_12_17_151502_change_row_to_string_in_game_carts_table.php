<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_carts', function (Blueprint $table) {
            $table->string('row')->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('game_carts', function (Blueprint $table) {
            $table->integer('row')->change(); 
        });
    }
};
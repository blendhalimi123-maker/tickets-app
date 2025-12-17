<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('api_game_id');
            $table->string('home_team');
            $table->string('away_team');
            $table->dateTime('match_date');
            $table->string('stadium');
            $table->string('stand');
            $table->integer('row');
            $table->integer('seat_number');
            $table->string('category');
            $table->decimal('price', 8, 2);
            $table->integer('quantity')->default(1);
            $table->string('status')->default('in_cart');
            $table->json('api_metadata')->nullable();
            $table->timestamp('reserved_until')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'api_game_id', 'stand', 'row', 'seat_number']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_carts');
    }
};
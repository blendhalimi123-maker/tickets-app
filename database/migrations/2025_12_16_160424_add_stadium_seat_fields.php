<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->string('seat_identifier')->nullable()->after('id');
            $table->string('stand')->nullable()->after('section');
            $table->string('category')->nullable()->after('stand');
            $table->decimal('price', 8, 2)->nullable()->after('category');
            $table->unsignedBigInteger('match_id')->nullable()->after('ticket_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropColumn(['seat_identifier', 'stand', 'category', 'price', 'match_id']);
        });
    }
};
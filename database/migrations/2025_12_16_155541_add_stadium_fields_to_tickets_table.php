<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('seat_id')->nullable()->after('seat_info');
            $table->string('stand')->nullable()->after('seat_id');
            $table->string('row')->nullable()->after('stand');
            $table->integer('seat_number')->nullable()->after('row');
            $table->string('category')->nullable()->after('seat_number');
            $table->unsignedBigInteger('fixture_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('match_id')->nullable()->after('fixture_id');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['seat_id', 'stand', 'row', 'seat_number', 'category', 'fixture_id', 'match_id']);
        });
    }
};
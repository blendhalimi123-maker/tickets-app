<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            if (!Schema::hasColumn('seats', 'fixture_id')) {
                $table->unsignedBigInteger('fixture_id')->nullable();
            }
            
            $table->string('seat_identifier')->nullable()->after('fixture_id');
            $table->string('seat_info')->nullable()->after('seat_identifier');
            $table->string('stand')->nullable()->after('seat_info');
            $table->string('category')->nullable()->after('stand');
            $table->decimal('price', 8, 2)->nullable()->after('category');
            $table->unsignedBigInteger('match_id')->nullable()->after('fixture_id');
        });
    }

    public function down(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $columns = ['seat_identifier', 'seat_info', 'stand', 'category', 'price', 'match_id'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('seats', $column)) {
                    $table->dropColumn($column);
                }
            }
            
        });
    }
};
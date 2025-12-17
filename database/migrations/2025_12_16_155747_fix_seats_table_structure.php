<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('seats')) {
            Schema::create('seats', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('fixture_id')->nullable();
                $table->unsignedBigInteger('match_id')->nullable();
                $table->string('seat_identifier')->nullable();
                $table->string('seat_info')->nullable();
                $table->string('stand')->nullable();
                $table->string('row')->nullable();
                $table->integer('number')->nullable();
                $table->string('category')->nullable();
                $table->decimal('price', 8, 2)->nullable();
                $table->boolean('is_booked')->default(false);
                $table->timestamps();
            });
        } else {
            Schema::table('seats', function (Blueprint $table) {
                if (!Schema::hasColumn('seats', 'fixture_id')) {
                    $table->unsignedBigInteger('fixture_id')->nullable();
                }
                if (!Schema::hasColumn('seats', 'match_id')) {
                    $table->unsignedBigInteger('match_id')->nullable()->after('fixture_id');
                }
                if (!Schema::hasColumn('seats', 'seat_identifier')) {
                    $table->string('seat_identifier')->nullable()->after('match_id');
                }
                if (!Schema::hasColumn('seats', 'seat_info')) {
                    $table->string('seat_info')->nullable()->after('seat_identifier');
                }
                if (!Schema::hasColumn('seats', 'stand')) {
                    $table->string('stand')->nullable()->after('seat_info');
                }
                if (!Schema::hasColumn('seats', 'category')) {
                    $table->string('category')->nullable()->after('stand');
                }
                if (!Schema::hasColumn('seats', 'price')) {
                    $table->decimal('price', 8, 2)->nullable()->after('category');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $columns = ['seat_identifier', 'seat_info', 'stand', 'category', 'price', 'match_id', 'fixture_id'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('seats', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
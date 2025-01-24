<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_degrees', function (Blueprint $table) {
            $table->id();
            $table->string('name_uz');
            $table->string('name_ru');
            $table->string('name_en');
            $table->timestamps();
        });

        DB::table('academic_degrees')->insert([
            [
                'name_en' => 'Bachelor',
                'name_uz' => 'Bakalavr',
                'name_ru' => 'Бакалавр',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_en' => 'Master',
                'name_uz' => 'Magistr',
                'name_ru' => 'Магистр',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_en' => 'PhD',
                'name_uz' => 'PhD',
                'name_ru' => 'Кандидат наук',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_degrees');
    }
};

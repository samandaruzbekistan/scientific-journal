<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_uz');
            $table->string('name_ru');
            $table->string('name_en');
            $table->bigInteger('price')->default(0);
            $table->timestamps();
        });

//        \Illuminate\Support\Facades\DB::table('article_types')->insert([
//            [
//                'name_uz' => 'Tabiiy fanlar',
//                'name_ru' => 'Естественные науки',
//                'name_en' => 'Natural sciences',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ],
//            [
//                'name_uz' => 'Texnika va muhandislik fanlari',
//                'name_ru' => 'Технические и инженерные науки',
//                'name_en' => 'Technical and engineering sciences',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ],
//            [
//                'name_uz' => 'Ijtimoiy va gumanitar fanlar',
//                'name_ru' => 'Социальные и гуманитарные науки',
//                'name_en' => 'Social and humanitarian sciences',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ],
//            [
//                'name_uz' => 'Iqtisodiyot va biznes',
//                'name_ru' => 'Экономика и бизнес',
//                'name_en' => 'Economics and business',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ],
//            [
//                'name_uz' => 'Tibbiyot va farmatsevtika',
//                'name_ru' => 'Медицина и фармацевтика',
//                'name_en' => 'Medicine and pharmacy',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ],
//            [
//                'name_uz' => 'Qishloq xo‘jaligi va ekologiya',
//                'name_ru' => 'Сельское хозяйство и экология',
//                'name_en' => 'Agriculture and ecology',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ],
//            [
//                'name_uz' => 'San’at va madaniyat',
//                'name_ru' => 'Искусство и культура',
//                'name_en' => 'Art and culture',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ]
//        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_types');
    }
};

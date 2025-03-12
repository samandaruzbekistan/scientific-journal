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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title_uz');
            $table->string('title_ru');
            $table->string('title_en');
            $table->longText('body_uz')->nullable();
            $table->longText('body_ru')->nullable();
            $table->longText('body_en')->nullable();
            $table->text('keywords_uz');
            $table->text('keywords_ru');
            $table->text('keywords_en');
            $table->text('abstract_uz');
            $table->text('abstract_ru');
            $table->text('abstract_en');
            $table->string('uz_file')->nullable();
            $table->string('ru_file')->nullable();
            $table->string('en_file')->nullable();
            $table->text('books');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('article_type_id');
            $table->foreign('article_type_id')->references('id')->on('article_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

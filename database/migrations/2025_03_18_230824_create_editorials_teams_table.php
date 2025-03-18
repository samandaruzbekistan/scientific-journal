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
        Schema::create('editorials_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_type_id');
            $table->foreign('article_type_id')->references('id')->on('article_types')->onDelete('cascade');
            $table->string('name');
            $table->string('json_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('editorials_teams');
    }
};

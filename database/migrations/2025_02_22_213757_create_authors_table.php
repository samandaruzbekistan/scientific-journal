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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('role')->default('author');
            $table->string('orcid')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('academic_degree_id')->nullable();
            $table->foreign('academic_degree_id')->references('id')->on('academic_degrees')->onDelete('set null');
            $table->text('institution')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};

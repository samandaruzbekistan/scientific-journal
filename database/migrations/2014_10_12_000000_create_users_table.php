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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('orcid');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->unsignedBigInteger('academic_degree_id')->nullable();
            $table->foreign('academic_degree_id')->references('id')->on('academic_degrees')->onDelete('set null');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('biography')->nullable();
            $table->string('institution')->nullable();
            $table->string('status')->default('preparing');
            $table->string('role')->default('user');
            $table->unsignedBigInteger('article_type_id')->nullable();
            $table->foreign('article_type_id')->references('id')->on('article_types')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

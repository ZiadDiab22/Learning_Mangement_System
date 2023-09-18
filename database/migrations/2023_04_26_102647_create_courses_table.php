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
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->boolean('Ended');
            $table->boolean('visible');
            $table->unsignedInteger('kind_id');
            $table->integer('price');
            $table->integer('classes_num');
            $table->string('name', 30);
            $table->string('disc', 75);
            $table->string('language', 75);
            $table->integer('subscribers');
            $table->integer('likes');
            $table->integer('dislikes');
            $table->float('rating');
            $table->string('img_url');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('kind_id')->references('id')
                ->on('kinds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

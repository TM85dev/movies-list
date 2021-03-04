<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('movie_id')->constrained('movies')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('rating')->default(0);
            $table->boolean('liked')->default(false);
            $table->boolean('rated')->default(false);
            $table->boolean('watched')->default(false);
            $table->boolean('to_watch')->default(false);
            $table->boolean('visible')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_movies');
    }
}

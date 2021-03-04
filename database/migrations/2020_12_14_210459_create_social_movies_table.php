<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('like')->default(0);
            $table->integer('visited')->default(0);
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
        Schema::dropIfExists('social_movies');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sum')->default(0);
            $table->integer('rating')->default(0);
            $table->integer('count')->default(0);
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
        Schema::dropIfExists('rate_movies');
    }
}

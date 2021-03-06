<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('movies');
            $table->string('title');
            $table->string('j_title');
            $table->string('romaji');
            $table->string('genre');
            $table->text('description');
            $table->date('release_date');
            $table->smallInteger('year');
            $table->integer('time');
            $table->string('cast')->nullable();
            $table->string('director')->nullable();
            $table->string('producer')->nullable();
            $table->string('images');
            $table->string('trailer_link')->nullable();
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
        Schema::dropIfExists('movies');
    }
}

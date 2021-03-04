<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serie_id')->constrained('series')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('social_series');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serie_id')->constrained('series')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('rate_series');
    }
}

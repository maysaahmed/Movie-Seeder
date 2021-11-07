<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedInteger('id');
            $table->primary('id');
            $table->string('title');
            $table->text('overview');
            $table->string('poster');
            $table->date('release_date');
            $table->double('popularity');
            $table->double('vote_average');
            $table->timestamps();
        });
        Schema::create('movies_categories', function (Blueprint $table) {
            $table->id();
            $table->Integer('movie_id')->unsigned();
            $table->Integer('category_id')->unsigned();

            $table->foreign('movie_id')->references('id')->on('movies');
            $table->foreign('category_id')->references('id')->on('categories');

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
        Schema::dropIfExists('movies_categories');
    }
}

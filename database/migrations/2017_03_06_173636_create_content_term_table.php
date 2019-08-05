<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_term', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('content_id')->unsigned();
            $table->unsignedInteger('term_id');

            $table->foreign('content_id')->references('id')->on('content')->onUpdate('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_term');
    }
}

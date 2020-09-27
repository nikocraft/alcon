<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContentTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_term', function (Blueprint $table) {
            $table->dropForeign(['content_id']);
            $table->foreign('content_id')->references('id')->on('content')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_term', function (Blueprint $table) {
            $table->dropForeign(['content_id']);
            $table->foreign('content_id')->references('id')->on('content')->onUpdate('cascade');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('widget_id')->nullable()->unsigned();
            $table->string('type');
            $table->mediumText('content')->nullable();
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('unique_id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->integer('user_id')->nullable()->unsigned();
            $table->json('meta')->nullable();
            
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('widget_id')->references('id')->on('widgets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_blocks');
    }
}

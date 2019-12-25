<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_subscribers', function (Blueprint $table) {
            $table->increments('id');
            $table->biginteger('content_id')->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->string('name')->nullable();
            $table->string('email');
            $table->tinyInteger('subscribe')->default(0);
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('comments_subscribers');
    }
}

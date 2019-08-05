<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->string('title')->nullable();
            $table->text('body');
            $table->tinyInteger('status')->default(1);
            $table->string('name');
            $table->string('email');
            $table->string('website')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->biginteger('content_id')->unsigned();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->ipAddress('visitor_ip')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}

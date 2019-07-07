<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->integer('content_type_id')->unsigned()->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('featured_image_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('access')->default(1);
            $table->string('layout', 30)->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('order')->nullable();
            $table->boolean('sticky')->default(false);
            $table->text('js')->nullable();
            $table->text('css')->nullable();
            $table->json('seo')->nullable();
            $table->json('resources')->nullable();
            $table->json('settings')->nullable();
            
            $table->dateTime('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('content_type_id')->references('id')->on('content_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

}

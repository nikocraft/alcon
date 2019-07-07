<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('name_singular');
            $table->string('slug', 20);
            $table->string('front_slug', 20)->nullable();
            $table->integer('type')->default(1); // 1 - non-repeating type like page, 2 - repeating type like blogs posts, etc
            $table->boolean('locked')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }
}

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
            $table->string('slug');
            $table->string('front_slug')->nullable();
            $table->smallInteger('type'); // 1 - non-repeating type like page, 2 - repeating type like blogs posts, etc
            $table->boolean('locked')->default(false);
            $table->schemalessAttributes('settings')->nullable();
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
        Schema::dropIfExists('content_types');
    }
}

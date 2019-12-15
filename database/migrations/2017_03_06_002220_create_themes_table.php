<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace')->unique();
            $table->string('folder')->unique();
            $table->integer('parent')->nullable();
            $table->string('name');
            $table->text('description');
            $table->string('org');
            $table->string('author');
            $table->string('version');
            $table->string('url');
            $table->string('releases_url');
            $table->string('download_url');
            $table->json('settings')->nullable();
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
        Schema::dropIfExists('themes');
    }
}

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
            $table->string('name')->unique();
            $table->string('folder')->unique();
            $table->string('org');
            $table->string('author');
            $table->string('url');
            $table->string('version');
            $table->text('description');
            $table->tinyInteger('screenshots')->default(1);
            $table->integer('parent')->nullable();
            $table->string('releases_url');
            $table->string('download_url');
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

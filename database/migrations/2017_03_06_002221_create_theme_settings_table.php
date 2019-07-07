<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('theme_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('key', 60);
            $table->string('label', 60);
            $table->string('value')->nullable();
            $table->string('type', 15)->default('string');
            $table->json('meta')->nullable();

            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parent_id')->references('id')->on('theme_settings');
        });
    }
}

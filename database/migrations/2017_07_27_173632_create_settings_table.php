<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('parent_id')->unsigned()->nullable();
            $table->string('section')->nullable();
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->json('meta')->nullable();

            $table->timestamps();
            // $table->foreign('parent_id')->references('id')->on('settings');
        });
    }
}

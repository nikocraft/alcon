<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned()->nullable();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->unsignedBigInteger('unique_id');
            $table->string('title', 30)->nullable();
            $table->string('subtitle')->nullable(); // short text displayed under label for some menus...
            $table->string('url')->nullable();
            $table->string('type', 50); // page menu-item, custom url menu-item, mega menu-item
            $table->string('icon')->nullable();
            $table->integer('order')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('parent_id')->references('unique_id')->on('menu_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}

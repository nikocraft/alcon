<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->unsignedBigInteger('content_id');
            $table->string('type');
            $table->mediumText('content')->nullable();
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('unique_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->schemalessAttributes('settings')->nullable();
            $table->json('meta')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['unique_id', 'content_id'])->unsigned();

            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}

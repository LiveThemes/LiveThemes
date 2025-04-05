<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('remixed_theme_id')->nullable();
            $table->integer('author_user_id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('major_version')->nullable();
            $table->string('minor_version')->nullable();
            $table->string('creator')->nullable();
            $table->text('elements')->nullable();
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

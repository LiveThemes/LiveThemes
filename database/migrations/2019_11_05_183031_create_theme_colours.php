<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeColours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_colours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('theme_id')->nullable();
            $table->string('element')->nullable();
            $table->integer('hue')->nullable();
            $table->integer('saturation')->nullable();
            $table->integer('lightness')->nullable();
            $table->integer('red')->nullable();
            $table->integer('green')->nullable();
            $table->integer('blue')->nullable();
            $table->string('hex')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theme_colours');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeDownloads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_downloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('theme_id');
            $table->integer('user_id');
            $table->string('ip', 39);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theme_downloads');
    }
}

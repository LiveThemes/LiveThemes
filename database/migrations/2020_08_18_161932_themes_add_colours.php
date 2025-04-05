<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ThemesAddColours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->integer('colour_1')->nullable();
            $table->integer('colour_2')->nullable();
            $table->integer('lightness')->nullable();
        });

        // Run
        // php artisan db:seed --class=ConvertThemeHue
        // php artisan db:seed --class=ConvertThemeLightness
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn('colour_1');
            $table->dropColumn('colour_2');
            $table->dropColumn('lightness');
        });
    }
}

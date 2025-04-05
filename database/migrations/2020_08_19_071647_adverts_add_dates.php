<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdvertsAddDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('background_position');
            $table->timestamp('from')->nullable()->after('updated_at');
            $table->timestamp('to')->nullable()->after('from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->dropColumn('from');
            $table->dropColumn('to');
            $table->string('type')->after('url');
            $table->string('background_position')->after('alt');
        });
    }
}

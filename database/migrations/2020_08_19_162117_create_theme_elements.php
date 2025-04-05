<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemeElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_elements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('element')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('min_version', 8, 2)->nullable();
            $table->decimal('max_version', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theme_elements');
    }
}

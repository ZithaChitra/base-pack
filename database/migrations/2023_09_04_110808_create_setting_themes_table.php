<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active');
            
            $table->timestamp('created')->useCurrent();
            $table->timestamp('modified')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * 
     */
    public function down()
    {
        Schema::dropIfExists('setting_themes');
    }
}

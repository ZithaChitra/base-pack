<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLevelAndOrderIndexToNavLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nav_links', function (Blueprint $table) {
            $table->integer('level')->default(1)->nullable();
            $table->integer('order_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nav_links', function (Blueprint $table) {
            $table->dropColumn('level');
            $table->dropColumn('order_index');
        });
    }
}

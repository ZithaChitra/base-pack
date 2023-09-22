<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nav_links', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->string('url');
            $table->string('icon')->default('')->nullable();
            $table->string('access')->default('');
            $table->boolean('visible')->default(true);
            $table->string('classes')->default('');
            $table->boolean('enabled')->default(true);
            $table->json('active')->default(json_encode([]));

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->foreign('parent_id')->references('id')->on('nav_links')->onDelete('cascade');

            $table->string('created_by');
            $table->string('modified_by');
            $table->timestamp('created')->useCurrent();
            $table->timestamp('modified')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nav_links');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('parts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('pn');
            $table->enum('source', ['mouser', 'digikey', 'other']);
            $table->string('source_pn');
            $table->string('manufacturer')->nullable();
            $table->string('description')->nullable();
            $table->integer('quantity')->default(0);

            $table->integer('location_broad');
            $table->integer('location_narrow');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::drop('parts');
    }
}

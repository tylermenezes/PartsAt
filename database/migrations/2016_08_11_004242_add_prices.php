<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('parts', function(Blueprint $table) {
            $table->decimal('price', 10, 5);
            $table->decimal('price_bulk', 10, 5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('parts', function(Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('price_bulk');
        });
    }
}

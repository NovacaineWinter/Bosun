<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StockDifferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockDifferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_id');
            $table->integer('delta');            
            $table->timestamps();
        });

        Schema::table('stock', function($tbl) {
            $tbl->integer('last_stock_check_timestamp')->default(0);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

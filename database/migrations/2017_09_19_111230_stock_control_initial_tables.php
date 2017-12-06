<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StockControlInitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('stock', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('category_id')->default(1);
            $table->integer('subcategory_id')->default(1);
            $table->string('description',5000)->nullable();
            $table->integer('qtyInStock');
            $table->integer('reorderQty')->nullable();
            $table->integer('orderToQty')->nullable();
            $table->decimal('retailEx',10,2);
            $table->decimal('retailInc',10,2);
            $table->integer('vatRateID');
            $table->string('building')->nullable();
            $table->string('isle')->nullable();
            $table->string('side')->nullable();
            $table->string('bay')->nullable();
            $table->string('shelf')->nullable();
            $table->string('position')->nullable();
            $table->integer('orderQty');
            $table->boolean('is_highlighted');
            $table->timestamps();
        });


        Schema::create('vat_rates', function($t) {
            $t->increments('id');
            $t->string('name');
            $t->decimal('multiplier',10,2);
            $t->string('text_for_multiplier');
            $t->timestamps();
        });


        Schema::create('bookedOutStock', function (Blueprint $z) {
            $z->increments('id');
            $z->integer('project_id');
            $z->integer('stock_id');
            $z->integer('qty');
            $z->decimal('exVatCost',10,2);
            $z->timestamps();
        });

        Schema::create('stockCategories', function(Blueprint $a) {
            $a->increments('id');
            $a->string('name');            
            $a->timestamps();
        });

        Schema::create('stockSubcategories', function(Blueprint $b) {
            $b->increments('id');
            $b->string('name');   
            $b->integer('category_id');         
            $b->timestamps();
        });

        Schema::create('stockCodes', function(Blueprint $c) {
            $c->increments('id');
            $c->integer('stock_id');
            $c->integer('supplier_id');
            $c->decimal('netCost',10,2)->nullable();
            $c->decimal('grossCost',10,2)->nullable();
            $c->string('code')->nullable();
            $c->boolean('prefered');
            $c->timestamps();
        });

        Schema::create('suppliers', function(Blueprint $d) {
            $d->increments('id');
            $d->string('alias')->nullable();
            $d->string('name');
            $d->string('address1')->nullable();
            $d->string('address2')->nullable();
            $d->string('address3')->nullable();
            $d->string('address4')->nullable();
            $d->string('address5')->nullable();
            $d->string('phone')->nullable();
            $d->string('mobile')->nullable(); 
            $d->string('fax')->nullable();           
            $d->string('email')->nullable();
            $d->string('website')->nullable();
            $d->string('contactName')->nullable();          
            $d->timestamps();
        });


        Schema::create('projects', function($j) {
            $j->increments('id');
            $j->string('name',50);
            $j->boolean('is_finished');
            $j->boolean('can_book_parts_to');
            $j->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('skill_id');
            $table->integer('project_id');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email',190)->unique();
            $table->string('password');
            $table->string('name');            
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email',190)->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });


        /*
        *       Create default entries in the tables
        */

        DB::table('vat_rates')->insert(
            array(
                'name'=>'Standard',
                'multiplier'=>1.2,
                'text_for_multiplier'=>"20%",                
                )
        );

        DB::table('vat_rates')->insert(
            array(
                'name'=>'Reduced',
                'multiplier'=>1.05,
                'text_for_multiplier'=>"5%",                
                )
        );

        DB::table('vat_rates')->insert(
            array(
                'name'=>'Zero',
                'multiplier'=>1,
                'text_for_multiplier'=>"0%",                
                )
        );       


        DB::table('stockCategories')->insert(
            array(
                'name'=>'Default Category',                
                )
        );

        DB::table('stockSubcategories')->insert(
            array(
                'name'=>'Default Subcategory',  
                'category_id'   =>1,              
                )
        );

        DB::table('suppliers')->insert(
            array(
                'name'=>'Default Supplier', 
                'alias' =>0,         
                )
        );

        /*
        *   This was for chandlery sales for NBC LTD
        */

       /* DB::table('projects')->insert(
                array(
                    'name'=>'General Sales',
                    'is_finished'=>0,
                    'can_book_parts_to'=>1
                    )
            );*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock');
        Schema::dropIfExists('vat_rates');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('bookedOutStock');
        Schema::dropIfExists('stockCategories');
        Schema::dropIfExists('stockSubcategories');
        Schema::dropIfExists('stockCodes');
        Schema::dropIfExists('suppliers');        
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');  
        Schema::dropIfExists('password_resets');
    }

}

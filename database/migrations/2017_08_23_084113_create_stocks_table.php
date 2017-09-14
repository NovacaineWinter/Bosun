<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\supplier;
use App\stock;
use App\stockCode;

class CreateStocksTable extends Migration
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
            $table->integer('category_id');
            $table->integer('subcategory_id');
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
        Schema::create('bookedOutParts', function (Blueprint $z) {
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


        DB::table('stockCategories')->insert(
            array(
                'name'=>'Default Category',                
                )
        );


        Schema::create('stockSubcategories', function(Blueprint $b) {
            $b->increments('id');
            $b->string('name');   
            $b->integer('category_id');         
            $b->timestamps();
        });

        DB::table('stockSubcategories')->insert(
            array(
                'name'=>'Default Subcategory',  
                'category_id'   =>1,              
                )
        );

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

        DB::table('suppliers')->insert(
            array(
                'name'=>'Default Supplier', 
                'alias' =>0,         
                )
        );

        Schema::create('consumedStock', function(blueprint $e) {
            $e->increments('id');
            $e->integer('stock_id');
            $e->integer('project_id');
            $e->decimal('unit_cost',10,2);
            $e->integer('qty_consumed');
            $e->timestamps();
        });


        $supplierFilePath='/home/matt/trading/suppliers.csv';

        $supplierFile = fopen($supplierFilePath,"r");
        if(fgetcsv($supplierFile)){
            while (($data = fgetcsv($supplierFile)) !== FALSE) {
                $newSupplier= "";
                $newSupplier = new supplier;

                $newSupplier->alias = $data[0];

                $newSupplier->name = $data[1];
                $newSupplier->address1 = $data[2];
                $newSupplier->address2 = $data[3];
                $newSupplier->address3 = $data[4];
                $newSupplier->address4 = $data[5];
                $newSupplier->address5 = $data[6];      
                $newSupplier->phone = $data[7];
                $newSupplier->mobile = $data[8];        
                $newSupplier->fax = $data[9];
                $newSupplier->email = $data[10];
                $newSupplier->website = $data[11];
                $newSupplier->contactName = $data[12];
                
                $newSupplier->save();                    

            }   
        }

/*
Insert stock 
*/
        $stockFilePath='/home/matt/trading/stock.csv';

        $stockFile = fopen($stockFilePath,"r");
        if(fgetcsv($stockFile)){
            while (($data = fgetcsv($stockFile)) !== FALSE) {

                $loc=explode('.',$data[10]);
                                
                $newStock= "";
                $newStock = new stock;
                $newStock->name = $data[4];
                $newStock->category_id=1;
                $newStock->subcategory_id =1;
                $newStock->description = $data[5].' '.$data[6].' '.$data[17];
                $newStock->qtyInStock = $data[7];
                $newStock->reorderQty = $data[8];
                $newStock->orderToQty = $data[9];
                $newStock->building = $loc[0];
                                
                if(count($loc>1)){
                    $newStock->bay = $loc[1];
                }
                if(count($loc)==3){
                    $newStock->shelf = $loc[2];
                }else{
                    $newStock->shelf = '';
                }
                
                $newStock->retailEx = substr(str_replace(',','',$data[13]),3);
                $newStock->retailInc = substr(str_replace(',','',$data[14]),3);
                $newStock->vatRateID = 1;  
                $newStock->bay = $loc[1];
                $newStock->shelf = $loc[2];
                $newStock->orderQty = 1;
                $newStock->is_highlighted = 0; 

                $newStock->save();  

                $stockCode = "";
                $stockCode = new stockCode;

                $suppInfo=supplier::where('alias','=',$data[15])->first();
                if($suppInfo){                  
                  $stockCode->supplier_id = $suppInfo->id;
                }else{
                    $stockCode->supplier_id = 1;
                }
                

                $stockCode->stock_id = $newStock->id;
                
                $stockCode->netCost = substr(str_replace(',','',$data[11]),3);
                $stockCode->grossCost = substr(str_replace(',','',$data[12]),3);
                $stockCode->code = $data[3];
                $stockCode->prefered = 1;  
                $stockCode->save();     

            }   
        }



    }   //end up function





    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock');
        Schema::dropIfExists('vat_rates');
        Schema::dropIfExists('bookedOutParts');
        Schema::dropIfExists('stockCategories');
        Schema::dropIfExists('stockSubcategories');
        Schema::dropIfExists('stockCodes');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('consumedStock');
    }
}

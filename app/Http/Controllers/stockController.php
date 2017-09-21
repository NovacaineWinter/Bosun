<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\supplier;
use App\project;
use App\stock;
use App\stockCode;
use App\stockCategory;
use App\stockSubcategory;
use App\bookedOutPart;


class stockController extends Controller
{
    public function searchSupplier(Request $request){


    	if($request->has('supplierKeyword')){

	    	$keyword = $request->get('supplierKeyword');
	    	$MatchedSuppliers = 
	    		supplier::where('name','like',$keyword)
			    	->take(10)
			    	->get();

			return view('inside.stock.ajax.list_suppliers')->with('suppliers',$MatchedSuppliers);  

    	}else{
    		return '';
    	}

	}

	public function stockValue(){
		$stock = stock::all();
		$projects = project::where('can_book_parts_to','=',1)->get();;

		$totalValue = 0;
		foreach($stock as $item){
			$currentItemValue = $item->qtyInStock * $item->supplierCodes->sortByDesc('prefered')->first()->netCost;
			$totalValue = $totalValue + $currentItemValue;
		}

		$toSendOn=array('storesValue'=>$totalValue);
		$toSendOn['projects']=array();

		foreach($projects as $project){
			$projectTotalValue=0;
			foreach($project->bookedOutParts as $part){
				$toAdd = $part->qty * $part->exVatCost;
				$projectTotalValue = $projectTotalValue + $toAdd; 
			}
			$toSendOn['projects'][]=array('project'=>$project,'wip'=>$projectTotalValue);
		}
		
		return view('inside.stock.value_summary')->with('data',$toSendOn);
	}


	public function searchStock(Request $request){
		// called by ajax only - sorts out search results and passes the results over to the view



		//check for category filter
		if(($request->has('category_filter')) && ($request->get('category_filter')>0)){

			$requestedCategory=$request->get('category_filter');
			$categorySQLOperator='=';
		}else{
			$requestedCategory='%';
			$categorySQLOperator='LIKE';
		}


		//check for subcategory filter
		if(($request->has('subcategory_filter')) && ($request->get('subcategory_filter')>0)){
			$requestedSubcategory = $request->get('subcategory_filter');
			$subcategorySQLOperator='=';
		}else{
			$requestedSubcategory='%';
			$subcategorySQLOperator='LIKE';
		}


		//check for seach keyword
		if(($request->has('stockKeyword')) && ($request->get('stockKeyword')!='')){
			$keyword = $request->get('stockKeyword');
		}else{
			$keyword='%';
		}


		if($request->has('showAll')){
			if($request->get('showAll')){
				$numToShow = 1000000000;
			}else{
				$numToShow = 50;
			}
		}else{
			$numToShow=50;
		}

		//check for supplier filter
		if(($request->has('supplier_filter')) && ($request->get('supplier_filter')>0)){

			$requestedSupplier=$request->get('supplier_filter');


			$codes=stockCode::			
				where('supplier_id','=',$requestedSupplier)
				
				->whereHas('item',function($queryOne) use($keyword){
				   	$queryOne->where(
					   	'name',
					   	'LIKE',
					   	'%'.$keyword.'%');
				})

				->whereHas('item',function($queryTwo) use($requestedCategory,$categorySQLOperator){
					$queryTwo->where('category_id',$categorySQLOperator,$requestedCategory);
				})
				
				->whereHas('item',function($queryThree) use($requestedSubcategory,$subcategorySQLOperator){					
					$queryThree->where('subcategory_id',$subcategorySQLOperator,$requestedSubcategory);
				})				
				->take($numToShow)
				->get();

			$items=$codes->pluck('item');

		}else{
			$items=stock::
				  where('name','LIKE','%'.$keyword.'%')
				->where('category_id',$categorySQLOperator,$requestedCategory)
				->where('subcategory_id',$subcategorySQLOperator,$requestedSubcategory)	
				->take($numToShow)			
				->get();
		}

		//find if the search string matches the stock code exactley
		
		if($request->has('stockKeyword') && $request->get('stockKeyword')!=''){
			$searchByStockCode = stockCode::where('code','=',$request->get('stockKeyword'))->get();

			if($searchByStockCode->count() == 1){
				$items = $searchByStockCode->pluck('item');
			}
		}

		if($items->count()){
			return view('inside.stock.ajax.list_stock_items')->with('items',$items); 	
		}else{
			return '';
		}
		 

	}


	public function stockHome(Request $request){
		//this is the main dashboard for the stock control system
		return view('inside.stock.stock_home');

	}


	public function stockItemDetail(Request $request){
		return view('inside.stock.ajax.item_detail')->with('request',$request);	
	}


	public function modals(Request $request){
		if($request->has('ajaxmethod')){
			switch($request->get('ajaxmethod')){


				case 'newItem':
					if($request->has('dataSubmitted')){
						//time to create a new part and all associated gubbins

						$newItem = new stock;

						$newItem->name = $request->get('name');
						$newItem->category_id = $request->get('category_id');
						$newItem->subcategory_id = $request->get('subcategory_id');
						$newItem->qtyInStock = $request->get('qtyInStock');
						$newItem->reorderQty = $request->get('reorderQty');
						$newItem->orderToQty = $request->get('orderToQty');
						$newItem->retailEx = $request->get('retailEx');
						$newItem->retailInc = $request->get('retailInc');
						$newItem->vatRateID = $request->get('vatRateID');
						$newItem->description = $request->get('description');
						$newItem->orderQty=1;
						$newItem->is_highlighted=0;

						if(!empty($request->get('stockLocation')['building'])){
							$newItem->building = $request->get('location')['building'];
						}
						if(!empty($request->get('stockLocation')['isle'])){
							$newItem->isle = $request->get('location')['isle'];
						}
						if(!empty($request->get('stockLocation')['side'])){
							$newItem->side = $request->get('location')['side'];
						}
						if(!empty($request->get('stockLocation')['bay'])){
							$newItem->bay = $request->get('location')['bay'];
						}
						if(!empty($request->get('stockLocation')['shelf'])){
							$newItem->shelf = $request->get('location')['shelf'];
						}
						if(!empty($request->get('stockLocation')['position'])){
							$newItem->position = $request->get('location')['position'];
						}
						$newItem->save();

						$supplierInfo= new stockCode;
						$supplierInfo->supplier_id = $request->get('supplier_id');
						$supplierInfo->stock_id = $newItem->id;
						$supplierInfo->prefered = 1;
						$supplierInfo->netCost = $request->get('costEx');
						$supplierInfo->grossCost = $request->get('costInc');
						$supplierInfo->code = $request->get('supplierStockCode');
						$supplierInfo->save();


					}else{
						return view('inside.stock.modals.new_item');
					}
					
					break;



				case 'newCategory':

					if($request->has('inputField')){
						$cat = new stockCategory;
						$cat->name=$request->get('inputField');
						$cat->save();
							//now make a general subcategory for our new category
						$subCat= new stockSubcategory;
						$subCat->category_id=$cat->id;
						$subCat->name='General';
						$subCat->save();
						return '';
					}else{
						return view('inside.stock.modals.new_category');	
					}
					
					break;


				case 'newSubcategory':
					if($request->has('inputField') && $request->has('parentCategory')){
						$subCat= new stockSubcategory;
						$subCat->category_id=$request->get('parentCategory');
						$subCat->name=$request->get('inputField');
						$subCat->save();
						return '';
					}else{
						return view('inside.stock.modals.new_subcategory');
					
					}					
					break;


				case 'newSupplier':

					if($request->has('inputField')){
						$supplier = new supplier;
						$supplier->name=$request->get('inputField');
						$supplier->save();
						return '';
					}else{
						return view('inside.stock.modals.new_supplier');	
					}					
					break;


				case 'newSupplierForItem':
					if($request->has('supplierID')){
						$itemSupplier = new stockCode;
						$itemSupplier->stock_id = $request->get('itemID');
						$itemSupplier->supplier_id = $request->get('supplierID');
						$itemSupplier->prefered=0;
						$itemSupplier->save();

						return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('itemID')));

					}else{
						return view('inside.stock.modals.new_supplier_for_part')->with('request',$request);
					}


				case 'bookOutItem':
					if($request->has('qtyToRemove') && $request->has('projectID') && $request->has('itemID')){
						$stockItem=stock::find($request->get('itemID'));
						$oldStock=$stockItem->qtyInStock;
						$newStock=$oldStock-$request->get('qtyToRemove');
						$stockItem->qtyInStock=$newStock;
						$stockItem->save();


						$alreadyExists = bookedOutPart::where('project_id','=',$request->get('projectID'))->where('stock_id','=',$request->get('itemID'))->first();
						
						if(!empty($alreadyExists)){
							$currentQty = $alreadyExists->qty;
							$alreadyExists->qty = $currentQty + $request->get('qtyToRemove');
							$alreadyExists->save();

						}else{

							$bookedOutItem= new bookedOutPart;
							$bookedOutItem->stock_id=$request->get('itemID');
							$bookedOutItem->project_id=$request->get('projectID');
							$bookedOutItem->qty = $request->get('qtyToRemove');

							//this may need to change for FIDO / LIFO stock controlling
							$itemCode=stockCode::where('stock_id','=',$request->get('itemID'))
								->where('prefered','=','1')->first();

							
							$bookedOutItem->exVatCost =$itemCode->grossCost;
							$bookedOutItem->save();
						}

						return view('inside.stock.ajax.item_detail')->with('item',$stockItem);

					}else{
						return view('inside.stock.modals.book_out_stock')->with('request',$request);
					}
					break;


				case 'addStockItem':

					if($request->has('qtyToAdd') && $request->has('itemID')){
						$stockItem=stock::find($request->get('itemID'));
						$oldStock=$stockItem->qtyInStock;
						$newStock=$oldStock+$request->get('qtyToAdd');
						$stockItem->qtyInStock=$newStock;
						$stockItem->save();

						return view('inside.stock.ajax.item_detail')->with('item',$stockItem);

					}else{
						return view('inside.stock.modals.add_stock_qty')->with('request',$request);
					}
					break;

				case 'unBookItem':
					if($request->has('targetID')){
						if($request->has('qtyToRemove') && $request->get('qtyToRemove')>0 && is_numeric($request->get('qtyToRemove'))){

							//change Qty to unbook - return items to stock level
							$oldBookedOutRecord = bookedOutPart::find($request->get('targetID'));

							if(!empty($oldBookedOutRecord)){

								//regardless, we need to increase the stock level on the main table
								$stockItem = stock::find($oldBookedOutRecord->stock_id);

								if(!empty($stockItem)){

									$oldQty = $stockItem->qtyInStock;
									$stockItem->qtyInStock = $oldQty+$request->get('qtyToRemove');
									$stockItem->save();

								}

								if($oldBookedOutRecord->qty == $request->get('qtyToRemove')){
									//need to delete bookedOutRecord 
									$oldBookedOutRecord->delete();
								}else{
									//update qty on bookedOutRecord
									$oldBookedQty = $oldBookedOutRecord->qty;
									$oldBookedOutRecord->qty = $oldBookedQty - $request->get('qtyToRemove');
									$oldBookedOutRecord->save();
								}


							}

						}else{
							return view('inside.stock.modals.change_booked_out_qty')->with('request',$request);
						}
					}
					break;
					
					
			}
		}
	}

	public function updateStockItem(Request $request){
		if($request->has('ajaxmethod') && $request->has('targetID') && $request->has('value')){
			switch($request->get('ajaxmethod')){
				case 'reorderQty':
					$stockItem=stock::find($request->get('targetID'));
					$stockItem->reorderQty = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'orderToQty':
					$stockItem=stock::find($request->get('targetID'));
					$stockItem->orderToQty = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'retailEx':
					$stockItem=stock::find($request->get('targetID'));
					$stockItem->retailEx = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'retailInc':
					$stockItem=stock::find($request->get('targetID'));
					$stockItem->retailInc = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'supplierNetCostChange':
					$code=stockCode::find($request->get('targetID'));
					$code->netCost = $request->get('value');
					$code->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($code->stock_id));
					break;

				case 'supplierGrossCostChange':
					$code=stockCode::find($request->get('targetID'));
					$code->grossCost = $request->get('value');
					$code->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($code->stock_id));
					break;


				case 'changePreferedSupplier':

					$codes=stockCode::where('stock_id','=',$request->targetID)->get();
					if(!empty($codes)){

						foreach($codes as $code){

							if($code->id==$request->get('value')){
								$code->prefered=1;
								$code->save();

							}else{
								$code->prefered=0;
								$code->save();

							}							
						}

					}
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'supplierPartNumber':
					$code=stockCode::find($request->get('targetID'));
					$code->code=$request->get('value');
					$code->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($code->stock_id));
					break;

				case 'changeItemName':
					$item=stock::find($request->get('targetID'));
					$item->name = $request->get('value');
					$item->save();
					//this is actually superflous as nothing will have changed but is needed for the handler function to prevent blank output 
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;


				case 'deleteSupplierForItem':

					if($request->has('targetID')){
						$stockCodeToDelete=stockCode::find($request->get('targetID'));
						$itemID=$stockCodeToDelete->stock_id;
						$stockCodeToDelete->delete();
					}

					return view('inside.stock.ajax.item_detail')->with('item',stock::find($itemID));

					break;


				case 'stockLocationBuilding':

					$stockItem=stock::find($request->get('targetID'));
					$stockItem->building = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;


				case 'stockLocationBuilding':

					$stockItem=stock::find($request->get('targetID'));
					$stockItem->building = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;	

				case 'stockLocationIsle':

					$stockItem=stock::find($request->get('targetID'));
					$stockItem->isle = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'stockLocationSide':

					$stockItem=stock::find($request->get('targetID'));
					$stockItem->side = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'stockLocationBay':

					$stockItem=stock::find($request->get('targetID'));
					$stockItem->bay = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'stockLocationShelf':

					$stockItem=stock::find($request->get('targetID'));
					$stockItem->shelf = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'stockLocationPosition':

					$stockItem=stock::find($request->get('targetID'));
					$stockItem->positon = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'stockDescription':
					$stockItem=stock::find($request->get('targetID'));
					$stockItem->description = $request->get('value');
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'highlightItem':
					$stockItem=stock::find($request->get('targetID'));
					$stockItem->is_highlighted = true;
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				case 'dehighlightItem':
					$stockItem=stock::find($request->get('targetID'));
					$stockItem->is_highlighted = false;
					$stockItem->save();
					return view('inside.stock.ajax.item_detail')->with('item',stock::find($request->get('targetID')));
					break;

				default:
					echo "Ajax method not recognised";
					break;
			}	
		}
	}


	public function generateSelects(Request $request){
		if($request->has('targetID') && $request->has('ajaxmethod') && $request->has('itemID')){
			switch($request->get('ajaxmethod')){

				case 'categorySelect':

					if($request->has('newCategoryID')){

						$item=stock::find($request->get('itemID'));
						$item->category_id = $request->get('newCategoryID');
						$item->save();
						echo $item->category->name;
						return;

					}else{
						return view('inside.stock.selects.category')->with('request',$request);
					}
					
					break;


				case 'subcategorySelect':

					if($request->has('parentID')){

						if($request->has('newSubcategoryID')){

							$item=stock::find($request->get('itemID'));
							$item->subcategory_id = $request->get('newSubcategoryID');
							$item->save();
							echo $item->subcategory->name;
							return;
						}else{
							return view('inside.stock.selects.subcategory')->with('request',$request);
						}
					}
					break;

			}
		}elseif($request->has('ajaxmethod')){
			//for the other more basic methods
			switch($request->get('ajaxmethod')){
				case 'getSubcategoryOptionsOnly':
					if($request->has('parentID')){
						return view('inside.stock.selects.subcategoryOptionsOnly')->with('request',$request);	
					}
					break;
			}
		}
	}



	public function bookedOutStock(Request $request){
		return view('inside.stock.view_booked_out_stock')->with('request',$request);
	}





}

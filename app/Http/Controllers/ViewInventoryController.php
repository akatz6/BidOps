<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use App\Property;
use App\Category;

class ViewInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['category'] = Category::where("category", "!=", "none")->get();
        $data['inventory']= Inventory::all();
        $data['property'] = Property::all();
        $data['propertySum'] = Property::where("type", "=", "numeric")->get();
        return response($data, 200);
    }

     /**
     * Get all Inventory info by category.
     *
     * @param  \App\Inventory  $categoryId
     * @return \Illuminate\Http\Response
     */

    public function filterByCategory($id){
        $data['category'] = Category::where("category", "!=", "none")->get();
        $data['inventory']= Inventory::where('category_id', "=", $id)->get();
        $data['property'] = Property::all();
        $data['propertySum'] = Property::where("type", "=", "numeric")->get();
        return response($data, 200);
    }

     /**
     * Sum by category.
     *
     * @param  \App\Inventory  $propertyId
     * @return \Illuminate\Http\Response
     */

    public function sumOnProperty($id, $propId){
        $this->checkIfText($id);
        $inventories;
        if($propId === '-1'){
            $inventories = Inventory::all("all_properties");
        }else{
            $inventories = Inventory::select('all_properties')->where("category_id", "=", $propId)->get();
        }
        $sum = 0;
        foreach($inventories as $inventory){
            $json = json_decode($inventory->all_properties);
            if(isset($json->$id)){
                $sum += intval($json->$id);
            }
        }
        return response($sum, 200);
    }

     /**
     * check if category is text.
     *
     * @param  \App\Inventory  $propertyId
     * @return \Illuminate\Http\Response
     */

     private function checkIfText($id){
        $property = Property::find($id);
        if($property->type === "text"){
            return response("Field is not numeric and cannot be summed", 300);
        }
     }
}

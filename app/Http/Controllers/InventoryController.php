<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Property;
use App\Category;
use Illuminate\Http\Request;

class InventoryController extends Controller
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
        $inventory = Inventory::all();
        return response($inventory, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateInfo($request);
        $info = $request->all();
        $this->validateProperties($info['all_properties']); 
        $this->doesExist($info);
        $this->saveInventory($info);
        return response('Ok', 200);
    }

    /*
        checks if inventory is in database
        @param info for inventory
        @return error message 
    */
    private function doesExist($info){
        $inventoryFound = Inventory::where('inventory', $info['inventory'])->first();
        if(count($inventoryFound ) > 0){
            return response('Inventory already exists', 300);
        }
    }

     /*
        saves inventory in database
        @param info for inventory
    */
    private function saveInventory($info){
        $inventory = new Inventory;
        $inventory->inventory = $info['inventory'];
        $inventory->category_id = $info['category_id'];
        $inventory->all_properties = json_encode($info['all_properties']);
        $inventory->save();
    }

     /*
        validates that properties are correct for numeric or text
        @param info for properties
        @return error message 
    */
    private function validateProperties($all_properties){
        $errors = [];
        $ids = $this->getIds();
        foreach ($all_properties as $key => $value){
            if($value !== 0 && $ids->contains($key) &&  (int)$value === 0){
                array_push ( $errors, $value);
            }
        }
        if(count($errors ) > 0){
            return response($errors[0], 300);
        }
    }

    /*
        validates info for database
        @param all use entered info
        @return error message 
    */
    private function validateInfo($request){
        $this->validate($request,[
            'inventory' => 'required',
            'category_id' => 'required | numeric',
            'all_properties' => 'required'
        ]); 
    }
     
    /*
        gets the ids for the properties
        @param none
        @return object with ids that are numeric
    */
    private function getIds(){
        $property = Property::where('type', 'numeric')->get();   
        return $property->pluck("id");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventory $inventory)
    {
        $this->validateInfo($request);
        $info = $request->all();
        $this->updateInventory($info);
        return response('Ok', 200);
    }

    /*
    update inventory in database
    @param info for inventory
    */
    private function updateInventory($info){
        $inventory =  Inventory::find($info['id']);
        $inventory->inventory = $info['inventory'];
        $inventory->category_id = $info['category_id'];
        $inventory->all_properties = json_encode($info['all_properties']);
        $inventory->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Inventory $inventory)
    {
        $info = $request->all();
        Inventory::find($info['id'])->delete();
        return response($data, 200);
    }

     /**
     * Get all Inventory info.
     *
     * @param  \App\Inventory  $inventoryId
     * @return \Illuminate\Http\Response
     */
    public function getInventoryById($id){
        $data['inventory'] = Inventory::find($id);
        $data['category'] =  Category::find($data['inventory']['category_id']);
        $data['properties'] = Property::all();
        return response($data, 200);
    }
}


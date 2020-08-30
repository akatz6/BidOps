<?php

namespace App\Http\Controllers;

use App\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
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
        $property = Property::all();
        return response($property, 200);
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
        $propertyFound = Property::where('property', $info['property'])->first();
        if (count($propertyFound) > 0) {
            return response('Property already exists', 300);
        }
        $this->saveProperty($info);
        return response('Ok', 200);
    }

    /*
        validates that properties are correct
        @param info for properties
        @return error message 
    */
    private function validateInfo($request)
    {
        $this->validate($request, [
            'property' => 'required',
            'type' => 'required'
        ]);
    }

    /*
        save the properties
        @param info for properties
        @return error message 
    */
    private function saveProperty($info)
    {
        $property = new Property;
        $property->property = $info['property'];
        $property->type = $info['type'];
        $property->save();
    }
}

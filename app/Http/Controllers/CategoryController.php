<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class CategoryController extends Controller
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
        $category = Category::where("category", "!=", "none")->get();
        return response($category, 200);
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
        $categoryFound = Category::where('category', $info['category'])->first();
        if(count($categoryFound ) > 0){
            return response('Category already exists', 300);
        }
        $this->saveInfo($info);
        return response('Ok', 200);
    }

    /*
        validates info for database
        @param all use entered info
        @return error message 
    */
    private function validateInfo($request){
        $this->validate($request,[
            'category' => 'required',
        ]);
    }

    /*
        validates info for database
        @param all use entered info
        @return error message 
    */
    private function saveInfo($info){
        $category = new Category;
        $category->category = $info['category'];
        $category->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request,[
            'id' => 'required',
            'selected' => 'required',
        ]); 
        $info = $request->all();
        if(isset($info["id"])){
            $category = Category::find($info["id"]);
            $category->category = $info['selected'];
            $category->save();
        }
        return response('Ok', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
        $info = $request->all();
        if(isset($info["id"])){
            $category = Category::find($info["id"]);
            $category->category = "none";
            $category->save();
        }
        return response('Ok', 200);
    }
}

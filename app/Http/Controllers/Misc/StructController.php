<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

use App\Models\Misc\Struct;

class StructController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Gets list of districts1926 for drop down list in JSON format
     * Test url: /misc/structs/list?structhiers[]=3
     * 
     * @return JSON response
     */
    public function list(Request $request)
    {
        $locale = app()->getLocale();
        $struct_name = '%'.$request->input('q').'%';
        $structhiers = (array)$request->input('structhiers');
//dd($region_id);
        $list = [];
        $structs = Struct::where(function($q) use ($struct_name){
                            $q->where('name_en',  'like',  $struct_name)
                              ->orWhere('name_ru','like',  $struct_name);
                         });
        if (sizeof($structhiers)) {
            $structs -> whereIn('structhier_id',$structhiers);
        }
        
        $structs = $structs->orderBy('name_'.$locale)->get();
                         
        foreach ($structs as $struct) {
            $list[]=['id'  => $struct->id, 
                     'text'=> $struct->name];
        }  
        return Response::json($list);
    }
}
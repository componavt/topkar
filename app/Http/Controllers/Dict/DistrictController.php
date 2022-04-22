<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dict\District;
use Response;

class DistrictController extends Controller
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
     * Gets list of districts for drop down list in JSON format
     * Test url: /dict/districts/list?region_id=1
     * 
     * @return JSON response
     */
    public function list(Request $request)
    {
        $locale = app()->getLocale();
        $district_name = '%'.$request->input('q').'%';
        $region_id = (int)$request->input('region_id');
//dd($region_id);
        $list = [];
        $districts = District::where(function($q) use ($district_name){
//                            $q->whereRaw('low(name_en) like low(?)', [$district_name])
                            $q->where('name_en','like', $district_name)
                              ->orWhere('name_ru','like', $district_name);
                         });
        if ($region_id) {                 
            $districts -> where('region_id',$region_id);
        }
        
        $districts = $districts->orderBy('name_'.$locale)->get();
                         
        foreach ($districts as $district) {
            $list[]=['id'  => $district->id, 
                     'text'=> $district->name];
        }  
        return Response::json($list);

    }
}

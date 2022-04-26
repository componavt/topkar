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

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name_en'  => 'max:150',
            'name_ru'  => 'required|max:150',
//            'district_id' => 'required|numeric',
            'region_id' => 'required|numeric',
        ]);
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

    public function simpleStore(Request $request)
    {
        $field = $request->locale == 'en' ? 'name_en' : 'name_ru';
        $this->validateRequest($request);
        $district = District::create($request->all());
        return Response::json(['id'=>$district->id, 'name'=>$district->{$field}, 
                'region_id'=>$district->region_id, 'region_name'=>$district->region->{$field}]);
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
     * Test url: /dict/districts/list?regions[]=1
     * 
     * @return JSON response
     */
    public function list(Request $request)
    {
        $locale = app()->getLocale();
        $district_name = '%'.$request->input('q').'%';
        $regions = (array)$request->input('regions');
//dd($region_id);
        $list = [];
        $districts = District::where(function($q) use ($district_name){
                            $q->where('name_en','like', $district_name)
                              ->orWhere('name_ru','like', $district_name);
                         });
        if (sizeof($regions)) {                 
            $districts -> whereIn('region_id',$regions);
        }
        
        $districts = $districts->orderBy('name_'.$locale)->get();
                         
        foreach ($districts as $district) {
            $list[]=['id'  =>  $district->id, 
                     'text'=>  $district->name];
        }  
        return Response::json($list);
    }
}

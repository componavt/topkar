<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

use App\Models\Dict\Selsovet1926;

class Selsovet1926Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $selsovets1926 = Selsovet1926::all();
        return view('dict.selsovets1926.index', compact('selsovets1926'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name_en'  => 'max:150',
            'name_ru'  => 'required|max:150',
            'name_krl'  => 'max:150',
            'district1926_id' => 'required|numeric',
        ]);
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

    public function simpleStore(Request $request)
    {
        $field = $request->locale == 'en' ? 'name_en' : 'name_ru';
        $this->validateRequest($request);
        $selsovet = Selsovet1926::create($request->all());
        return Response::json(['id'=>$selsovet->id, 'name'=>$selsovet->{$field}, 
                'district_id'=>$selsovet->district1926_id, 'district_name'=>$selsovet->district1926->{$field}]);
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
     * Gets list of selsovets1926 for drop down list in JSON format
     * Test url: /dict/selsovets1926/list?districts[]=1&regions[]=1
     * 
     * @return JSON response
     */
    public function list(Request $request)
    {
        $locale = app()->getLocale();
        $selsovet_name = '%'.$request->input('q').'%';
        $districts = (array)$request->input('districts');
        $regions = (array)$request->input('regions');

        $list = [];
        $selsovets = Selsovet1926::where(function($q) use ($selsovet_name){
                            $q->where(  'name_en','like',  $selsovet_name)
                              ->orWhere('name_ru','like',  $selsovet_name);
                         });
        if (sizeof($districts)) {                 
            $selsovets -> whereIn('district1926_id',$districts);
        }
        if (sizeof($regions)) {                 
            $selsovets -> whereIn('district1926_id', function ($q) use ($regions) {
                        $q->select('id')->from('districts1926')
                           ->whereIn('region_id', $regions);
            });
        }
//dd(to_sql($settlements));
        $selsovets = $selsovets->orderBy('name_'.$locale)->get();
                         
        foreach ($selsovets as $selsovet) {
            $list[]=['id'  => $selsovet->id, 
                     'text'=> $selsovet->name];
        }  
        return Response::json($list);
    }
}

<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

use App\Models\Dict\Settlement1926;

class Settlement1926Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$settlements1926 = Settlement1926::all();
        
        $page = (int)$request->input('page') 
              ? (int)$request->input('page') : 1;
        $portion = 10;
        
        $settlements1926 = Settlement1926::paginate($portion);
        return view('dict.settlements1926.index', compact('settlements1926', 'portion', 'page' ));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name_en'  => 'max:150',
            'name_ru'  => 'required|max:150',
            'name_krl'  => 'max:150',
            'selsovet_id' => 'required|numeric',
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
        $settlement = Settlement1926::create($request->all());
        return Response::json(['id'=>$settlement->id, 'name'=>$settlement->{$field}, 
                'selsovet_id'=>$settlement->selsovet_id, 'selsovet_name'=>$settlement->selsovet1926->{$field}]);
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
     * Gets list of settlements1926 for drop down list in JSON format
     * Test url: /dict/settlements1926/list?selsovets[]=1&districts[]=1&regions[]=1
     * 
     * @return JSON response
     */
    public function list(Request $request)
    {
        $locale = app()->getLocale();
        $settlement_name = '%'.$request->input('q').'%';
        $selsovets = (array)$request->input('selsovets');
        $districts = (array)$request->input('districts');
        $regions = (array)$request->input('regions');

        $list = [];
        $settlements = Settlement1926::where(function($q) use ($settlement_name){
                            $q->where('name_en','like', $settlement_name)
                              ->orWhere('name_ru','like', $settlement_name);
                         });
        if (sizeof($selsovets)) {                 
            $settlements -> whereIn('selsovet_id',$selsovets);
        }
        if (sizeof($districts) || sizeof($regions)) {                 
            $settlements -> whereIn('selsovet_id', function ($q) use ($districts, $regions) {
                $q->select('id')->from('selsovets1926')
                  ->whereIn('district1926_id', $districts);
                if (sizeof($regions)) {
                    $q->whereIn('district1926_id', function ($q2) use ($regions) {
                        $q2->select('id')->from('districts1926')
                           ->whereIn('region_id', $regions);
                    });
                }
            });
        }
//dd(to_sql($settlements));
        $settlements = $settlements->orderBy('name_'.$locale)->get();
                         
        foreach ($settlements as $settlement) {
            $list[]=['id'  => $settlement->id, 
                     'text'=> $settlement->name];
        }  
        return Response::json($list);
    }
}

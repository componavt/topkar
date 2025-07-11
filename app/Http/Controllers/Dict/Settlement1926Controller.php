<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
use Response;

use App\Models\Dict\District1926;
use App\Models\Dict\Region;
use App\Models\Dict\Selsovet1926;
use App\Models\Dict\Settlement1926;

class Settlement1926Controller extends Controller
{
    public $url_args=[];
    public $args_by_get='';
    
     /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('is_editor', 
                         ['except' => ['index','slist','show']]);
        $this->url_args = Settlement1926::urlArgs($request);  
        
        $this->args_by_get = search_values_by_URL($this->url_args);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $settlements1926 = Settlement1926::search($url_args);
        $n_records = $settlements1926->count();        
        $settlements1926 = $settlements1926->paginate($this->url_args['portion']);
         
        $region_values = [''=>NULL] + Region::getList();
        $district1926_values = District1926::getList();
        $selsovet1926_values = Selsovet1926::getList();
        $sort_values = Settlement1926::sortList();
        
        return view('dict.settlements1926.index', 
                compact('district1926_values', 'n_records', 'region_values', 'selsovet1926_values',
                        'settlements1926', 'sort_values', 'args_by_get', 'url_args'));
     }

    public function validateRequest(Request $request) {
        return $this->validate($request, [
            'name_en'  => 'max:150',
            'name_ru'  => 'required|max:150',
            'name_krl'  => 'max:150',
            'selsovet_id' => 'required|numeric',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $region_values = [''=>NULL] + Region::getList();
        $district1926_values = [''=>NULL] + District1926::getList();
        $selsovet1926_values = [''=>NULL] + Selsovet1926::getList();

        return view('dict.settlements1926.create', 
                compact('district1926_values', 'region_values', 
                        'selsovet1926_values', 'args_by_get', 'url_args'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $settlement = Settlement1926::create($this->validateRequest($request)); 
        
        return Redirect::to(route('settlements1926.show', $settlement).($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
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
    public function show(int $id)
    {
        $settlement= Settlement1926::findOrFail($id);
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('dict.settlements1926.show', 
                compact('settlement', 'args_by_get', 'url_args'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $settlement= Settlement1926::findOrFail($id);

        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $region_values = [''=>NULL] + Region::getList();
        $district1926_values = [''=>NULL] + District1926::getList();
        $selsovet1926_values = [''=>NULL] + Selsovet1926::getList();
        
        return view('dict.settlements1926.edit', 
                compact('district1926_values', 'region_values', 'selsovet1926_values', 
                        'settlement', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $settlement= Settlement1926::findOrFail($id);
        $settlement->fill($this->validateRequest($request))->save();
       
        return Redirect::to(route('settlements1926.show', $settlement).($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $error = false;
        $status_code = 200;
        $result =[];
        if($id != "" && $id > 0) {
            try{
                $obj = Settlement1926::find($id);
                if($obj){
                    $obj_name = $obj->name;
                    if ($obj->toponyms()->count()) {
                        $result['error_message'] = \Lang::get('toponym.settlement1926_can_not_be_removed');
                    } else {
                        $obj->delete();
                        $result['message'] = \Lang::get('toponym.settlement1926_removed', ['name'=>$obj_name]);
                    }
                }
                else{
                    $error = true;
                    $result['error_message'] = \Lang::get('messages.record_not_exists');
                }
          }catch(\Exception $ex){
                    $error = true;
                    $status_code = $ex->getCode();
                    $result['error_code'] = $ex->getCode();
                    $result['error_message'] = $ex->getMessage();
                }
        }else{
            $error =true;
            $status_code = 400;
            $result['message']='Request data is empty';
        }
        
        if ($error) {
                return Redirect::to(route('settlements1926.index').$this->args_by_get)
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('settlements1926.index').$this->args_by_get)
                  ->withSuccess($result['message']);
        }
    }
    
    /**
     * Gets list of settlements1926 for drop down list in JSON format
     * Test url: /dict/settlements1926/list?selsovets[]=1&districts[]=1&regions[]=1
     * 
     * @return JSON response
     */
    public function slist(Request $request)
    {
        $locale = app()->getLocale();
        $settlement_name = '%'.$request->input('q').'%';
        $selsovets = request_arr($request->selsovets);
        $districts = request_arr($request->districts);
        $regions = request_arr($request->regions);

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
                $q->select('id')->from('selsovets1926');
                if (sizeof($districts)) {
                    $q->whereIn('district1926_id', $districts);
                }
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
    
    public function listWithDistricts(Request $request)
    {
        $locale = app()->getLocale();
        $settlement_name = '%'.$request->input('q').'%';
        $regions = array_remove_null($request->input('regions'));
//dd($regions, $districts);

        $list = [];
        $settlements = Settlement1926::where(function($q) use ($settlement_name){
                            $q->where('name_en','like', $settlement_name)
                              ->orWhere('name_ru','like', $settlement_name);
                         });
        if (sizeof($regions)) {                 
            $settlements -> whereIn('selsovet_id', function ($q) use ($regions) {
                $q->select('id')->from('selsovets1926')
                  ->whereIn('district1926_id', function ($q2) use ($regions) {
                        $q2->select('id')->from('districts1926')
                           ->whereIn('region_id', $regions);
                    });
            });
        }
//dd(to_sql($settlements));
        $settlements = $settlements->orderBy('name_'.$locale)->get();
                         
        foreach ($settlements as $settlement) {
            $name = $settlement->name;
            if ($settlement->selsovet1926) {
                $name .= ' ('. $settlement->selsovet1926->name. ')';
            }
            $list[]=['id'  => $settlement->id, 
                     'text'=> $name];
        }  
        return Response::json($list);
    }
}

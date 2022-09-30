<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
use Response;

use App\Models\Dict\District;
use App\Models\Dict\Region;
use App\Models\Dict\Settlement;

class SettlementController extends Controller
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
                         ['except' => ['index','list','show']]);
        $this->url_args = Settlement::urlArgs($request);  
        
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
        
        $settlements = Settlement::search($url_args);
        $n_records = $settlements->count();        
        $settlements = $settlements->paginate($this->url_args['portion']);
         
        $region_values = [''=>NULL] + Region::getList();
        $district_values = District::getList();
        
        return view('dict.settlements.index', 
                compact('district_values', 'n_records', 'region_values', 
                        'settlements', 'args_by_get', 'url_args'));
     }

    public function validateRequest(Request $request) {
        return $this->validate($request, [
            'name_en'  => 'max:150',
            'name_ru'  => 'required|max:150',
            'name_krl'  => 'max:150',
            'geotype_id' => 'integer'
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
        $district_values = [''=>NULL] + District::getList();
        $type_values = [''=>NULL] + Settlement::getTypeList();

        return view('dict.settlements.create', 
                compact('district_values', 'region_values', 'type_values', 
                        'args_by_get', 'url_args'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $obj = Settlement::create($this->validateRequest($request)); 
        
        return Redirect::to(route('settlements.index').($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
    }

    public function simpleStore(Request $request)
    {
        $field = $request->locale == 'en' ? 'name_en' : 'name_ru';
        $this->validateRequest($request);
        $settlement = Settlement::create($request->all());
        $settlement->districts()->attach($request->district_id);
        return Response::json(['id'=>$settlement->id, 'name'=>$settlement->{$field}, 
                'district_id'=>$request->district_id, 'district_name'=> District::find($request->district_id)->{$field} ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $settlement= Settlement::findOrFail($id);
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('dict.settlements.show', 
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
        $settlement= Settlement::findOrFail($id);

        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $region_values = [''=>NULL] + Region::getList();
        $district_values = [''=>NULL] + District::getList();
        $type_values = [''=>NULL] + Settlement::getTypeList();
        
        return view('dict.settlements.edit', 
                compact('district_values', 'region_values', 'settlement', 
                        'type_values', 'args_by_get', 'url_args'));
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
        $settlement= Settlement::findOrFail($id);
        $settlement->fill($this->validateRequest($request))->save();
        $settlement->saveDistricts($request->districts);
       
        return Redirect::to(route('settlements.index', $settlement).($this->args_by_get))
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
                $obj = Settlement::find($id);
                if($obj){
                    $obj_name = $obj->name;
                    if ($obj->toponyms()->count()) {
                        $result['error_message'] = \Lang::get('toponym.settlement_can_not_be_removed');
                    } else {
                        $obj->districts()->detach();
                        $obj->events()->detach();
                        $obj->delete();
                        $result['message'] = \Lang::get('toponym.settlement_removed', ['name'=>$obj_name]);
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
                return Redirect::to(route('settlements.index').$this->args_by_get)
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('settlements.index').$this->args_by_get)
                  ->withSuccess($result['message']);
        }
    }
    
    /**
     * Gets list of settlements for drop down list in JSON format
     * Test url: /dict/settlements/list?selsovets[]=1&districts[]=1&regions[]=1
     * 
     * @return JSON response
     */
    public function list(Request $request)
    {
        $locale = app()->getLocale();
        $settlement_name = '%'.$request->input('q').'%';
        $districts = array_remove_null($request->input('districts'));
        $regions = array_remove_null($request->input('regions'));
//dd($regions, $districts);

        $list = [];
        $settlements = Settlement::where(function($q) use ($settlement_name){
                            $q->where('name_en','like', $settlement_name)
                              ->orWhere('name_ru','like', $settlement_name);
                         });
        if (sizeof($districts) || sizeof($regions)) {                 
            $settlements -> whereIn('id', function ($q) use ($districts, $regions) {
                $q->select('settlement_id')->from('district_settlement');
                if (sizeof($districts)) {
                    $q->whereIn('district_id', $districts);
                }
                if (sizeof($regions)) {
                    $q->whereIn('district_id', function ($q2) use ($regions) {
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

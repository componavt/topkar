<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
use Response;

use App\Models\Dict\District1926;
use App\Models\Dict\Selsovet1926;
use App\Models\Dict\Region;

class Selsovet1926Controller extends Controller
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
        $this->url_args = Selsovet1926::urlArgs($request);  
        
        $this->args_by_get = search_values_by_URL($this->url_args);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $selsovets1926 = Selsovet1926::search($url_args);
        $n_records = $selsovets1926->count();        
        $selsovets1926 = $selsovets1926->paginate($this->url_args['portion']);
         
        $region_values = [''=>NULL] + Region::getList();
        $district1926_values = District1926::getList();
        
        return view('dict.selsovets1926.index', 
                compact('district1926_values', 'n_records', 'region_values', 
                        'selsovets1926', 'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        return $this->validate($request, [
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
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $region_values = [''=>NULL] + Region::getList();
        $district1926_values = [''=>NULL] + District1926::getList();

        return view('dict.selsovets1926.create', 
                compact('district1926_values', 'region_values', 
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
        $obj = Selsovet1926::create($this->validateRequest($request)); 
        
        return Redirect::to(route('selsovets1926.index').($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
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
        Redirect::to(route('selsovets1926.index').($this->args_by_get));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $selsovet= Selsovet1926::findOrFail($id);
       
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $region_values = [''=>NULL] + Region::getList();
        $district1926_values = [''=>NULL] + District1926::getList();
        
        return view('dict.selsovets1926.edit', 
                compact('district1926_values', 'region_values', 'selsovet', 
                        'args_by_get', 'url_args'));
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
        $selsovet = Selsovet1926::find($id);
        $selsovet->fill($this->validateRequest($request))->save();
       
        return Redirect::to(route('selsovets1926.index', $selsovet).($this->args_by_get))
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
                $obj = Selsovet1926::find($id);
                if($obj){
                    $obj_name = $obj->name;
                    if ($obj->settlements1926()->count() || $obj->toponyms()->count()) {
                        $result['error_message'] = \Lang::get('toponym.selsovet1926_can_not_be_removed');
                    } else {
                        $obj->delete();
                        $result['message'] = \Lang::get('toponym.selsovet1926_removed', ['name'=>$obj_name]);
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
                return Redirect::to(route('selsovets1926.index').$this->args_by_get)
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('selsovets1926.index').$this->args_by_get)
                  ->withSuccess($result['message']);
        }
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
        $districts = array_remove_null($request->input('districts'));
        $regions = array_remove_null($request->input('regions'));

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

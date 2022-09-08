<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Redirect;

use App\Models\Dict\District1926;
use App\Models\Dict\Region;

class District1926Controller extends Controller
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
        $this->url_args = District1926::urlArgs($request);  
        
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
        
        $districts1926 = District1926::search($url_args);
        $n_records = $districts1926->count(); 
        
        $districts1926 = $districts1926->paginate($this->url_args['portion']);
        
        $region_values = [''=>NULL] + Region::getList();
        
        return view('dict.districts1926.index', compact('districts1926', 'n_records', 'region_values', 
                        'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        return $this->validate($request, [
            'name_en'  => 'max:150',
            'name_ru'  => 'required|max:150',
            'region_id' => 'required|numeric',
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

        return view('dict.districts1926.create', 
                compact('region_values', 'args_by_get', 'url_args'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $obj = District1926::create($this->validateRequest($request)); 
        
        return Redirect::to(route('districts1926.index').($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
    }

    public function simpleStore(Request $request)
    {
        $field = $request->locale == 'en' ? 'name_en' : 'name_ru';
        $this->validateRequest($request);
        $district = District1926::create($request->all());
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
        Redirect::to(route('districts1926.index').($this->args_by_get));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $district1926 = District1926::find($id);
        
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $region_values = [''=>NULL] + Region::getList();
        return view('dict.districts1926.edit', 
                compact('district1926', 'region_values', 'args_by_get', 'url_args'));
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
        $district1926 = District1926::find($id);
        $district1926->fill($this->validateRequest($request))->save();
       
        return Redirect::to(route('districts1926.index', $district1926).($this->args_by_get))
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
                $district1926 = District1926::find($id);
                if($district1926){
                    $district1926_name = $district1926->name;
                    $district1926->delete();
                    $result['message'] = \Lang::get('toponym.district_removed', ['name'=>$district1926_name]);
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
                return Redirect::to(route('districts1926.index').($this->args_by_get))
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('districts1926.index').($this->args_by_get))
                  ->withSuccess($result['message']);
        }
    }
    
    /**
     * Gets list of districts1926 for drop down list in JSON format
     * Test url: /dict/districts1926/list?regions[]=1
     * 
     * @return JSON response
     */
    public function list(Request $request)
    {
        $locale = app()->getLocale();
        $district_name = '%'.$request->input('q').'%';
        $regions = array_remove_null($request->input('regions'));

        $list = [];
        $districts = District1926::where(function($q) use ($district_name){
                            $q->where('name_en',  'like',  $district_name)
                              ->orWhere('name_ru','like',  $district_name);
                         });
        if (sizeof($regions)) {
            $districts -> whereIn('region_id',$regions);
        }
        
        $districts = $districts->orderBy('name_'.$locale)->get();
                         
        foreach ($districts as $district) {
            $list[]=['id'  => $district->id, 
                     'text'=> $district->name];
        }  
        return Response::json($list);
    }
}

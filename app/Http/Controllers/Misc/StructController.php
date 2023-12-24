<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Redirect;

use App\Models\Misc\Struct;
use App\Models\Misc\Structhier;

class StructController extends Controller
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
                         ['except' => ['index','structList','show']]);
        $this->url_args = Struct::urlArgs($request);  
        
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
        $locale = app()->getLocale();
        
        $structs = Struct::search($url_args);
        $n_records = $structs->count();        
        $structs = $structs->paginate($this->url_args['portion']);
         
        $structhier_values = Structhier::getGroupedList();
        $sort_values = Struct::sortList();
        
        return view('misc.structs.index', 
                compact('locale', 'n_records', 'structs', 'structhier_values', 
                        'sort_values', 'args_by_get', 'url_args'));
     }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name_ru'  => 'required|max:150',
            'structhier_id'  => 'integer',
        ]);
        return $request->all();
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
        
        $structhier_values = Structhier::getGroupedList();

        return view('misc.structs.create', 
                compact('structhier_values', 'args_by_get', 'url_args'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $struct = Struct::create($this->validateRequest($request)); 
        
        return Redirect::to(route('structs.index').($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
    }

    public function simpleStore(Request $request)
    {
//        $field = $request->locale == 'en' ? 'name_en' : 'name_ru';
        $this->validateRequest($request);
        $struct = Struct::create($request->all());
        return Response::json(['id'=>$struct->id, 'name'=>$struct->name_ru, 
                'structhier_id'=>$struct->structhier_id, 
                'structhier_name'=> Structhier::find($struct->structhier_id)->nameToString() ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Redirect::to(route('structs.index', $struct).'?search_id=$id');        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $struct= Struct::findOrFail($id);

        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $structhier_values = Structhier::getGroupedList();
        
        return view('misc.structs.edit', 
                compact('struct', 'structhier_values', 'args_by_get', 'url_args'));
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
        $struct= Struct::findOrFail($id);
        $struct->fill($this->validateRequest($request))->save();
       
        return Redirect::to(route('structs.index', $struct).($this->args_by_get))
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
                $obj = Struct::find($id);
                if($obj){
                    $obj_name = $obj->name;
                    if ($obj->toponyms()->count()) {
                        $result['error_message'] = \Lang::get('misc.struct_can_not_be_removed');
                    } else {
                        $obj->delete();
                        $result['message'] = \Lang::get('misc.struct_removed', ['name'=>$obj_name]);
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
                return Redirect::to(route('structs.index').$this->args_by_get)
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('structs.index').$this->args_by_get)
                  ->withSuccess($result['message']);
        }
    }
    
    /**
     * Gets list of districts1926 for drop down list in JSON format
     * Test url: /misc/structs/list?structhiers[]=3
     * 
     * @return JSON response
     */
    public function structList(Request $request)
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

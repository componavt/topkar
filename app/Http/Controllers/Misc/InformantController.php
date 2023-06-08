<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Redirect;

use App\Models\Misc\Informant;

class InformantController extends Controller
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
                         ['except' => ['index','show']]);
        $this->url_args = Informant::urlArgs($request);  
        
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
        
        $informants = Informant::search($url_args);
        $n_records = $informants->count();        
        $informants = $informants->paginate($this->url_args['portion']);
                
        return view('misc.informants.index', 
                compact('informants', 'n_records', 'args_by_get', 'url_args'));
   }

    public function validateRequest(Request $request) {
        return $this->validate($request, [
            'name_ru'  => 'required|max:150',
            'name_en'  => 'max:150',
            'birth_date'  => 'nullable|numeric',
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
        
        return view('misc.informants.create', compact('args_by_get', 'url_args'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $obj = Informant::create($this->validateRequest($request)); 
        
        return Redirect::to(route('informants.index').($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
    }

    public function simpleStore(Request $request)
    {
        $field = $request->locale == 'en' ? 'name_en' : 'name_ru';
        $this->validateRequest($request);
        $informant = Informant::where('name_ru', 'like', $request->name_ru)->first();
        if ($informant) {
            return Response::json(['id'=>$informant->id, 'name'=>$informant->{$field}]);
        }
        $informant = Informant::where('name_en', 'like', $request->name_en)->first();
        if ($informant) {
            return Response::json(['id'=>$informant->id, 'name'=>$informant->{$field}]);
        }
        $informant = Informant::create($request->all());
        return Response::json(['id'=>$informant->id, 'name'=>$informant->{$field}]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Informant $informant)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('misc.informants.show', 
                compact('informant', 'args_by_get', 'url_args'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Informant $informant)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('misc.informants.edit', 
                compact('informant', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Informant $informant)
    {
        $informant->fill($this->validateRequest($request))->save();
       
        return Redirect::to(route('informants.index', $informant).($this->args_by_get))
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
                $informant = Informant::find($id);
                if($informant){
                    $informant_name = $informant->name;
                    $informant->events()->detach();
                    $informant->delete();
                    $result['message'] = \Lang::get('misc.informant_removed', ['name'=>$informant_name]);
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
                return Redirect::to(route('informants.index'))
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('informants.index'))
                  ->withSuccess($result['message']);
        }
    }
    
    /**
     * Gets list of districts for drop down list in JSON format
     * Test url: /misc/informants/list
     * 
     * @return JSON response
     */
    public function informantList(Request $request)
    {
        $locale = app()->getLocale();
        $name = '%'.$request->input('q').'%';

        $list = [];
        $informants = Informant::where(function($q) use ($name){
                            $q->where('name_en','like', $name)
                              ->orWhere('name_ru','like', $name);
                         })
                        ->orderBy('name_'.$locale)->get();
                         
        foreach ($informants as $informant) {
            $list[]=['id'  =>  $informant->id, 
                     'text'=>  $informant->informantString()];
        }  
        return Response::json($list);
    }
}

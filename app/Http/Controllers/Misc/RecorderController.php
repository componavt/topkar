<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Response;
use Redirect;

use App\Models\Misc\Recorder;

class RecorderController extends Controller
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
        $this->url_args = Recorder::urlArgs($request);  
        
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
        
        $recorders = Recorder::search($url_args);
        $n_records = $recorders->count();        
        $recorders = $recorders->paginate($this->url_args['portion']);
                
        return view('misc.recorders.index', 
                compact('recorders', 'n_records', 'args_by_get', 'url_args'));
   }

    public function validateRequest(Request $request) {
        return $this->validate($request, [
            'name_ru'  => 'required|max:150',
            'name_en'  => 'max:150',
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
        
        return view('misc.recorders.create', compact('args_by_get', 'url_args'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $obj = Recorder::create($this->validateRequest($request)); 
        
        return Redirect::to(route('recorders.index').($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
    }

    public function simpleStore(Request $request)
    {
        $field = $request->locale == 'en' ? 'name_en' : 'name_ru';
        $this->validateRequest($request);
        $recorder = Recorder::where('name_ru', 'like', $request->name_ru)->first();
        if ($recorder) {
            return Response::json(['id'=>$recorder->id, 'name'=>$recorder->{$field}]);
        }
        $recorder = Recorder::where('name_en', 'like', $request->name_en)->first();
        if ($recorder) {
            return Response::json(['id'=>$recorder->id, 'name'=>$recorder->{$field}]);
        }
        $recorder = Recorder::create($request->all());
        return Response::json(['id'=>$recorder->id, 'name'=>$recorder->{$field}]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Recorder $recorder)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('misc.recorders.show', 
                compact('recorder', 'args_by_get', 'url_args'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Recorder $recorder)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('misc.recorders.edit', 
                compact('recorder', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recorder $recorder)
    {
        $recorder->fill($this->validateRequest($request))->save();
       
        return Redirect::to(route('recorders.index', $recorder).($this->args_by_get))
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
                $recorder = Recorder::find($id);
                if($recorder){
                    $recorder_name = $recorder->name;
                    $recorder->events()->detach();
                    $recorder->delete();
                    $result['message'] = \Lang::get('misc.recorder_removed', ['name'=>$recorder_name]);
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
                return Redirect::to(route('recorders.index'))
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('recorders.index'))
                  ->withSuccess($result['message']);
        }
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
        $name = '%'.$request->input('q').'%';

        $list = [];
        $recorders = Recorder::where(function($q) use ($name){
                            $q->where('name_en','like', $name)
                              ->orWhere('name_ru','like', $name);
                         })
                        ->orderBy('name_'.$locale)->get();
                         
        foreach ($recorders as $recorder) {
            $list[]=['id'  =>  $recorder->id, 
                     'text'=>  $recorder->name];
        }  
        return Response::json($list);
    }
}

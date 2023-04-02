<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
use Response;

use App\Models\Misc\Source;

class SourceController extends Controller
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
        $this->url_args = Source::urlArgs($request);  
        
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
        
        $sources = Source::search($url_args);
        $n_records = $sources->count();        
        $sources = $sources->paginate($this->url_args['portion']);
                
        return view('misc.sources.index', 
                compact('sources', 'n_records', 'args_by_get', 'url_args'));
   }

    public function validateRequest(Request $request) {
        return $this->validate($request, [
            'short_ru'  => 'max:32',
            'name_ru'  => 'required|max:64',
            'short_en'  => 'max:32',
            'name_en'  => 'max:64',
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
        
        return view('misc.sources.create', compact('args_by_get', 'url_args'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $obj = Source::create($this->validateRequest($request)); 
        
        return Redirect::to(route('sources.index').($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
    }

    public function simpleStore(Request $request)
    {
        $field = $request->locale == 'en' ? 'name_en' : 'name_ru';
        $this->validateRequest($request);
        if ($request->name_ru) {
            $source = Source::where('name_ru', 'like', $request->name_ru)->first();
            if ($source) {
                return Response::json(['id'=>$source->id, 'name'=>$source->{$field}]);
            }
        } elseif ($request->short_ru) {
            $source = Source::where('short_ru', 'like', $request->short_ru)->first();
            if ($source) {
                return Response::json(['id'=>$source->id, 'name'=>$source->{$field}]);
            }
        } elseif ($request->name_en) {
            $source = Source::where('name_en', 'like', $request->name_en)->first();
            if ($source) {
                return Response::json(['id'=>$source->id, 'name'=>$source->{$field}]);
            }
        } elseif ($request->short_en) {
            $source = Source::where('short_en', 'like', $request->short_en)->first();
            if ($source) {
                return Response::json(['id'=>$source->id, 'name'=>$source->{$field}]);
            }
        }
        $source = Source::create($request->all());
        return Response::json(['id'=>$source->id, 'name'=>$source->{$field}]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Source $source)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('misc.sources.show', 
                compact('source', 'args_by_get', 'url_args'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Source $source)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('misc.sources.edit', 
                compact('source', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Source $source)
    {
        $source->fill($this->validateRequest($request))->save();
       
        return Redirect::to(route('sources.index', $source).($this->args_by_get))
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
                $source = Source::find($id);
                if($source){
                    $source_name = $source->name;
                    $source->delete();
                    $result['message'] = \Lang::get('misc.source_removed', ['name'=>$source_name]);
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
                return Redirect::to(route('sources.index'))
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('sources.index'))
                  ->withSuccess($result['message']);
        }
    }
}

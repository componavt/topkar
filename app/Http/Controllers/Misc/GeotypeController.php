<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
use Response;

use App\Models\Misc\Geotype;

class GeotypeController extends Controller
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
        $this->url_args = Geotype::urlArgs($request);  
        
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
        
        $geotypes = Geotype::search($url_args);
        $n_records = $geotypes->count();        
        $geotypes = $geotypes->paginate($this->url_args['portion']);
        $sort_values = Geotype::sortList();
                
        return view('misc.geotypes.index', 
                compact('geotypes', 'n_records', 'sort_values', 'args_by_get', 'url_args'));
   }

    public function validateRequest(Request $request) {
        return $this->validate($request, [
            'short_ru'  => 'max:32',
            'name_ru'  => 'required|max:64',
            'desc_ru'  => 'max:255',
            'short_en'  => 'max:32',
            'name_en'  => 'max:64',
            'desc_en'  => 'max:255',
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
        
        return view('misc.geotypes.create', compact('args_by_get', 'url_args'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $obj = Geotype::create($this->validateRequest($request)); 
        
        return Redirect::to(route('geotypes.index').($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
    }

    public function simpleStore(Request $request)
    {
        $field = $request->locale == 'en' ? 'name_en' : 'name_ru';
        $this->validateRequest($request);
        if ($request->name_ru) {
            $geotype = Geotype::where('name_ru', 'like', $request->name_ru)->first();
            if ($geotype) {
                return Response::json(['id'=>$geotype->id, 'name'=>$geotype->{$field}]);
            }
        } elseif ($request->short_ru) {
            $geotype = Geotype::where('short_ru', 'like', $request->short_ru)->first();
            if ($geotype) {
                return Response::json(['id'=>$geotype->id, 'name'=>$geotype->{$field}]);
            }
        } elseif ($request->name_en) {
            $geotype = Geotype::where('name_en', 'like', $request->name_en)->first();
            if ($geotype) {
                return Response::json(['id'=>$geotype->id, 'name'=>$geotype->{$field}]);
            }
        } elseif ($request->short_en) {
            $geotype = Geotype::where('short_en', 'like', $request->short_en)->first();
            if ($geotype) {
                return Response::json(['id'=>$geotype->id, 'name'=>$geotype->{$field}]);
            }
        }
        $geotype = Geotype::create($request->all());
        return Response::json(['id'=>$geotype->id, 'name'=>$geotype->{$field}]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Geotype $geotype)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('misc.geotypes.show', 
                compact('geotype', 'args_by_get', 'url_args'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Geotype $geotype)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        return view('misc.geotypes.edit', 
                compact('geotype', 'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Geotype $geotype)
    {
        $geotype->fill($this->validateRequest($request))->save();
       
        return Redirect::to(route('geotypes.index', $geotype).($this->args_by_get))
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
                $geotype = Geotype::find($id);
                if($geotype){
                    $geotype_name = $geotype->name;
                    $geotype->delete();
                    $result['message'] = \Lang::get('misc.geotype_removed', ['name'=>$geotype_name]);
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
                return Redirect::to(route('geotypes.index'))
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('geotypes.index'))
                  ->withSuccess($result['message']);
        }
    }
}

<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;

use App\Models\Dict\Toponym;
use App\Models\Dict\Region;
use App\Models\Dict\District;

class ToponymController extends Controller
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
        // permission= corpus.edit, redirect failed users to /corpus/text/, authorized actions list:
        //$this->middleware('auth:corpus.edit,/corpus/text/', 
        //                 ['only' => ['create','store','edit','update','destroy']]);
        $this->url_args = Toponym::urlArgs($request);  
        
        $this->args_by_get = search_values_by_URL($this->url_args);
    }
    
    /**
     * Display toponyms.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $toponyms = Toponym::search($url_args);
        $n_records = $toponyms->count();        
        $toponyms = $toponyms->paginate($this->url_args['portion']);
        
        $region_values = Region::getList();
        $district_values = District::getList();
        $sort_values = Toponym::sortList();

        //$region_values = ["" => NULL] + Region::getList();
        return view('dict.toponyms.index', 
                compact('district_values', 'region_values', 'sort_values', 
                        'toponyms', 'n_records', 'args_by_get', 'url_args' ));
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
        
        $region_values = Region::getList();
        $district_values = District::getList();
        return view('dict.toponyms.create', 
                compact('district_values', 'region_values', 'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name'  => 'required|max:255'
            ]);
        $data = $request->only('name', 'DISTRICT_ID', 'SETTLEMENT');
        return $data;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $toponym = Toponym::create($this->validateRequest($request)); 
        
        return Redirect::to(route('toponyms.show', $toponym).($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Toponym $toponym)
    {
        $args_by_get = $this->args_by_get;
        return view('dict.toponyms.show', 
                    compact('toponym', 'args_by_get'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Toponym $toponym)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $region_values = [''=>NULL] + Region::getList();
        $district_values = [''=>NULL] + District::getList();
        return view('dict.toponyms.edit', 
                compact('district_values', 'region_values', 'toponym', 
                        'args_by_get', 'url_args'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Toponym $toponym)
    {
        $toponym->fill($this->validateRequest($request))->save();
        
        return Redirect::to(route('toponyms.show', $toponym).($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.created_success'));        
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
}

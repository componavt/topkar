<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
//        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $toponyms = Toponym::search($url_args);
        $n_records = $toponyms->count();
        
        $toponyms = $toponyms->paginate($this->url_args['portion']);
        $region_values = Region::getList();
        $district_values = District::getList();

        //$region_values = ["" => NULL] + Region::getList();
        return view('dict.toponyms.index', 
                compact('district_values', 'region_values', 'toponyms', 'n_records', 'url_args' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $url_args = $this->url_args;
        
        $region_values = Region::getList();
        $district_values = District::getList();
        return view('dict.toponyms.create', compact('district_values', 'region_values', 'url_args'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $toponym = Toponym::find($id);
        return view('dict.toponyms.show', compact('toponym'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

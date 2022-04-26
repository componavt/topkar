<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;

use App\Models\Aux\Geotype;
use App\Models\Aux\EthnosTerritory;
use App\Models\Aux\EtymologyNation;
use App\Models\Aux\Struct;
use App\Models\Aux\Structhier;

use App\Models\Dict\District;
use App\Models\Dict\District1926;
use App\Models\Dict\Region;
use App\Models\Dict\Selsovet1926;
use App\Models\Dict\Settlement1926;
use App\Models\Dict\Toponym;

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
        
        $district_values = District::getList();
        $district1926_values = District1926::getList();
        $ethnos_territory_values = EthnosTerritory::getList();
        $etymology_nation_values = EtymologyNation::getList();
        $geotype_values = Geotype::getList();
        $region_values = Region::getList();
        $selsovet1926_values = Selsovet1926::getList();
        $settlement1926_values = Settlement1926::getList();
        $sort_values = Toponym::sortList();
        $struct_values = Struct::getList();
        $structhier_values = Structhier::getGroupedList();

        return view('dict.toponyms.index', 
                compact('district_values', 'district1926_values', 
                        'ethnos_territory_values', 'etymology_nation_values', 'geotype_values', 
                        'region_values', 'selsovet1926_values', 'settlement1926_values', 
                        'sort_values', 'struct_values', 'structhier_values', 'toponyms', 
                        'n_records', 'args_by_get', 'url_args' ));
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
        
        $district_values = [''=>NULL] + District::getList();
        $district1926_values = [''=>NULL] + District1926::getList();
        $ethnos_territory_values = [''=>NULL] + EthnosTerritory::getList();
        $etymology_nation_values = [''=>NULL] + EtymologyNation::getList();
        $geotype_values = [''=>NULL] + Geotype::getList();
        $region_values = [''=>NULL] + Region::getList();
        $selsovet1926_values = [''=>NULL] + Selsovet1926::getList();
        $settlement1926_values = [''=>NULL] + Settlement1926::getList();
        $struct_values = [''=>NULL] + Struct::getList();
        $structhier_values = Structhier::getGroupedList();
        
        for ($i=0; $i<4; $i++) {
            $structs[]=[];
            $structhiers[]=[];            
        }

        return view('dict.toponyms.create', 
                compact('district_values', 'district1926_values', 
                        'ethnos_territory_values', 'etymology_nation_values', 
                        'geotype_values', 'region_values', 'selsovet1926_values', 
                        'settlement1926_values', 'structs', 'structhiers', 
                        'struct_values', 'structhier_values', 'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name'  => 'required|max:255'
            ]);
        $data = $request->only('name', 'district_id', 'SETTLEMENT', 'settlement1926_id', 
                    'geotype_id', 'etymology', 'etymology_nation_id', 'ethnos_territory_id', 'caseform');
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
        
//dd($toponym->selsovet1926_id, $toponym->district1926_id, $toponym->region1926_id);

        $ethnos_territory_values = [''=>NULL] + EthnosTerritory::getList();
        $etymology_nation_values = [''=>NULL] + EtymologyNation::getList();
        $geotype_values = [''=>NULL] + Geotype::getList();
        
        $region_values = [''=>NULL] + Region::getList();
        $district_values = [''=>NULL] + District::getList();
        $district1926_values = [''=>NULL] + District1926::getList();
        $selsovet1926_values = [''=>NULL] + Selsovet1926::getList();
        $settlement1926_values = [''=>NULL] + Settlement1926::getList();
        $struct_values = [''=>NULL] + Struct::getList();
        $structhier_values = Structhier::getGroupedList();
        
        $structs = $structhiers = [];
        foreach ($toponym->structs as $struct) {
            $structs[]=[$struct->id];
            $structhiers[]=[$struct->structhier_id];
        }
        for ($i=sizeof($toponym->structs); $i<4; $i++) {
            $structs[]=[];
            $structhiers[]=[];            
        }
        return view('dict.toponyms.edit', 
                compact('district_values', 'district1926_values', 
                        'ethnos_territory_values', 'etymology_nation_values', 
                        'geotype_values', 'region_values', 'selsovet1926_values', 
                        'settlement1926_values', 'structs', 'structhiers', 
                        'struct_values', 'structhier_values', 'toponym', 'args_by_get', 'url_args'));
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
        $structs = array_filter((array)$request->structs, 'strlen');        
        $toponym->structs()->sync($structs);
        
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

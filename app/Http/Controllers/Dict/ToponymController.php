<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;

use App\Models\Dict\District;
use App\Models\Dict\District1926;
use App\Models\Dict\Region;
use App\Models\Dict\Selsovet1926;
use App\Models\Dict\Settlement;
use App\Models\Dict\Settlement1926;
use App\Models\Dict\Toponym;

use App\Models\Misc\Geotype;
use App\Models\Misc\EthnosTerritory;
use App\Models\Misc\EtymologyNation;
use App\Models\Misc\Informant;
use App\Models\Misc\Recorder;
use App\Models\Misc\Struct;
use App\Models\Misc\Structhier;

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
        $this->middleware('is_editor', 
                         ['except' => ['index','show']]);
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
//        dd($request->input('search_source'));        
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
        $informant_values = Informant::getList();
        $recorder_values = Recorder::getList();
        $region_values = Region::getList();
        $selsovet1926_values = Selsovet1926::getList();
        $settlement_values = Settlement::getList();
        $settlement1926_values = Settlement1926::getList();
        $sort_values = Toponym::sortList();
        $struct_values = Struct::getList();
        $structhier_values = Structhier::getGroupedList();

        return view('dict.toponyms.index', 
                compact('district_values', 'district1926_values', 
                        'ethnos_territory_values', 'etymology_nation_values', 
                        'geotype_values', 'informant_values', 'recorder_values',
                        'region_values', 'selsovet1926_values', 
                        'settlement_values', 'settlement1926_values', 'sort_values', 
                        'struct_values', 'structhier_values', 'toponyms', 
                        'n_records', 'args_by_get', 'url_args' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        
        $region_values = [''=>NULL] + Region::getList();
        $region_value = [(int)$request->input('region_id')];
        $region1926_value = [(int)$request->input('region1926_id')];
        
        $district_values = [''=>NULL] + District::getList();
        $district_value = [(int)$request->input('district_id')];
        $district1926_values = [''=>NULL] + District1926::getList();
        $district1926_value = [(int)$request->input('district1926_id')];
         
        $selsovet1926_values = [''=>NULL] + Selsovet1926::getList();
        $selsovet1926_value = [(int)$request->input('selsovet1926_id')];
        
        $settlement_values = [''=>NULL] + Settlement::getList();
        $settlement_value = (array)$request->input('settlement_id');
        $settlement1926_values = [''=>NULL] + Settlement1926::getList();
        $settlement1926_value = [(int)$request->input('settlement1926_id')];

        $ethnos_territory_values = [''=>NULL] + EthnosTerritory::getList();
        $etymology_nation_values = [''=>NULL] + EtymologyNation::getList();
        $geotype_values = [''=>NULL] + Geotype::getList();
        $informant_values = Informant::getList();
        $recorder_values = Recorder::getList();
        
        $struct_values = [''=>NULL] + Struct::getList();
        $structhier_values = Structhier::getGroupedList();
        $type_values = [''=>NULL] + Settlement::getTypeList();
        
        for ($i=0; $i<4; $i++) {
            $structs[]=[];
            $structhiers[]=[];            
        }

        return view('dict.toponyms.create', 
                compact('district_value', 'district_values', 'district1926_value', 
                        'district1926_values', 'ethnos_territory_values', 
                        'etymology_nation_values', 'geotype_values',  
                        'informant_values', 'recorder_values', 'region_value',
                        'region_values', 'region1926_value', 'selsovet1926_value', 
                        'selsovet1926_values', 'settlement_value', 
                        'settlement_values', 'settlement1926_value', 
                        'settlement1926_values', 'structs', 'structhiers', 
                        'struct_values', 'structhier_values', 'type_values', 
                        'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name'  => 'required|max:255',
//            'wd'    => 'integer'
            ]);
        
        // todo: validate wd starts from 'Q', contains only numbers.
        
        $data = $request->only('name', 'district_id', 'settlement1926_id', 
                    'geotype_id', 'etymology', 'etymology_nation_id', 'legend',
                    'ethnos_territory_id', 'caseform', 'main_info', 'folk', 'wd');
        
        $data['name'] = to_right_form($data['name']);
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
        $toponym = Toponym::storeData($this->validateRequest($request), $request); 
       
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
        $informant_values = Informant::getList();
        $recorder_values = Recorder::getList();
        
        $region_values = [''=>NULL] + Region::getList();
        $district_values = [''=>NULL] + District::getList();
        $district1926_values = [''=>NULL] + District1926::getList();
        $selsovet1926_values = [''=>NULL] + Selsovet1926::getList();
        $settlement_values = [''=>NULL] + Settlement::getList();
        $settlement1926_values = [''=>NULL] + Settlement1926::getList();
        $struct_values = [''=>NULL] + Struct::getList();
        $structhier_values = Structhier::getGroupedList();
        $type_values = [''=>NULL] + Settlement::getTypeList();
        
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
                        'geotype_values',  'informant_values', 'recorder_values', 
                        'region_values', 'selsovet1926_values', 'settlement_values', 
                        'settlement1926_values', 'structs', 'structhiers', 
                        'struct_values', 'structhier_values', 'toponym', 
                        'type_values', 'args_by_get', 'url_args'));
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
//dd($request->events);        
        $toponym->updateData($this->validateRequest($request), $request);
        
        return Redirect::to(route('toponyms.show', $toponym).($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.updated_success'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $error = false;
        $status_code = 200;
        $result =[];
        if($id > 0) {
            try{
                $obj = Toponym::find($id);
                if($obj){
                    $obj_name = $obj->name;
                    $obj->remove();
                    $result['message'] = \Lang::get('toponym.toponym_removed', ['name'=>$obj_name]);
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
                return Redirect::to(route('toponyms.index').($this->args_by_get))
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('toponyms.index').($this->args_by_get))
                  ->withSuccess($result['message']);
        }
    }
}

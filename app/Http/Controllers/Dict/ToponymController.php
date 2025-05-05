<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
//use Response;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\TblWidth;

use App\Models\Dict\District;
use App\Models\Dict\District1926;
use App\Models\Misc\Event;
use App\Models\Dict\Lang;
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
use App\Models\Misc\Source;
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
                         ['except' => ['index','show', 'nLadoga', 'onMap', 'shaidomozero', 'nladogaOnMap',
                                    'withWD', 'withWrongnames', 'withLegends', 'withCoords']]);
        $this->url_args = Toponym::urlArgs($request);  
        
        $this->args_by_get = search_values_by_URL($this->url_args);
        
    }
    
    /**
     * Display toponyms.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $informant_values = Informant::getList();
        $recorder_values = Recorder::getList();
        $region_values = Region::getList();
        $selsovet1926_values = Selsovet1926::getList();
        $settlement_values = Settlement::getList();
        $settlement1926_values = Settlement1926::getList();
        $sort_values = Toponym::sortList();
        $struct_values = Struct::getList();
        $structhier_values = Structhier::getGroupedList();
        $source_values = [''=>NULL] + Source::getList(true);

        return view('dict.toponyms.index', 
                compact('district_values', 'district1926_values', 
                        'ethnos_territory_values', 'etymology_nation_values', 
                        'geotype_values', 'informant_values', 'recorder_values',
                        'region_values', 'selsovet1926_values', 
                        'settlement_values', 'settlement1926_values', 'sort_values', 
                        'source_values', 'struct_values', 'structhier_values', 
                        'toponyms', 'n_records', 'args_by_get', 'url_args' ));
    }
    
    public function onMap()
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        $limit = 1000;
        if (empty($url_args['map_height'])) {
            $url_args['map_height'] = 1700;
        }

        list($total_rec, $show_count, $objs, $limit, $bounds, $url_args) 
                = Toponym::forMap($limit, $url_args);                  

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
        $source_values = [''=>NULL] + Source::getList(true);

        return view('dict.toponyms.on_map', 
                compact('bounds', 'district_values', 'district1926_values', 'objs', 'limit',
                        'ethnos_territory_values', 'etymology_nation_values', 
                        'geotype_values', 'informant_values', 'recorder_values',
                        'region_values', 'selsovet1926_values', 'show_count',
                        'settlement_values', 'settlement1926_values', 'sort_values', 
                        'source_values', 'struct_values', 'structhier_values', 
                        'total_rec', 'args_by_get', 'url_args' ));
    }

    public function withWD()
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $toponyms = Toponym::search($url_args)->whereNotNull('wd');
        $n_records = $toponyms->count();        
        $toponyms = $toponyms->paginate($this->url_args['portion']);
        
        $district_values = District::getList();
        $geotype_values = Geotype::getList();
        $region_values = Region::getList();
        $settlement_values = Settlement::getList();
        $sort_values = Toponym::sortList();

        return view('dict.toponyms.with_wd', 
                compact('district_values', 'geotype_values', 'region_values', 
                        'settlement_values', 'sort_values',
                        'toponyms', 'n_records', 'args_by_get', 'url_args' ));
    }
    
    public function withWrongnames()
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $toponyms = Toponym::search($url_args)->whereIn('id', function ($q) {
            $q->select('toponym_id')->from('wrongnames');
        });
        $n_records = $toponyms->count();        
        $toponyms = $toponyms->paginate($this->url_args['portion']);
        
        $district_values = District::getList();
        $geotype_values = Geotype::getList();
        $region_values = Region::getList();
        $settlement_values = Settlement::getList();
        $sort_values = Toponym::sortList();

        return view('dict.toponyms.with_wrongnames', 
                compact('district_values', 'geotype_values', 'region_values', 
                        'settlement_values', 'sort_values',
                        'toponyms', 'n_records', 'args_by_get', 'url_args' ));
    }
    
    public function withLegends() {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $toponyms = Toponym::search($url_args)
                ->where(function($q) {
                    $q->whereNotNull('legend')
                      ->orWhereIn('id', function($q2) {
                          $q2->select('toponym_id')->from('text_toponym');
                      });
                });
        $n_records = $toponyms->count();        
        $toponyms = $toponyms->paginate($this->url_args['portion']);
        
        $district_values = District::getList();
        $geotype_values = Geotype::getList();
        $region_values = Region::getList();
        $settlement_values = Settlement::getList();
        $sort_values = Toponym::sortList();

        return view('dict.toponyms.with_legends', 
                compact('district_values', 'geotype_values', 'region_values', 
                        'settlement_values', 'sort_values',
                        'toponyms', 'n_records', 'args_by_get', 'url_args' ));
    }
    
    public function withCoords()
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $toponyms = Toponym::whereNotNull('latitude')->whereNotNull('longitude');
        $n_records = $toponyms->count();        
        $toponyms = $toponyms->orderBy('latitude', 'desc')->get()->groupBy(['latitude', 'longitude']);
        
//dd($toponyms);        
        $objs = [];
        $bounds = ['min_lat'=>null, 'min_lon'=>null, 'max_lat'=>null, 'max_lon'=>null];
        foreach ($toponyms as $lat => $long_list) {
            foreach ($long_list as $lon => $toponym_list) {
                $popup = [];
                foreach ($toponym_list as $toponym) {
                   $popup[] = to_show($toponym->name, 'toponym', $toponym).($toponym->geotype ? '<br>'.$toponym->geotype->name : ''); 
                }
            }
            $objs[] = ['lat'=>$lat, 'lon'=>$lon, 'popup' => join('<br>', $popup)]; 
            if ($bounds['min_lat'] === null || $lat < $bounds['min_lat']) $bounds['min_lat'] = $lat;
            if ($bounds['max_lat'] === null || $lat > $bounds['max_lat']) $bounds['max_lat'] = $lat;
            if ($bounds['min_lon'] === null || $lon < $bounds['min_lon']) $bounds['min_lon'] = $lon;
            if ($bounds['max_lon'] === null || $lon > $bounds['max_lon']) $bounds['max_lon'] = $lon;
        }
        
//dd($objs);        
        return view('dict.toponyms.with_coords', 
                compact('bounds', 'objs', 'n_records', 'args_by_get', 'url_args' ));
    }
    
    public function shaidomozero()
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        $lat1 = 62.68;
        $lat2 = 62.74;
        $lon1 = 34.13;
        $lon2 = 34.23;
        $objs = [55998, 56007, 55996, 55990, 56024, 56020, 56019, 56207];

        $toponyms = Toponym::whereNotNull('latitude')->whereNotNull('longitude');
        if ($objs) {
            $toponyms->whereIn('id', $objs);
        } else {
            $toponyms->where('latitude', '<', $lat2)
                ->where('latitude', '>', $lat1)
                ->where('longitude', '<', $lon2)
                ->where('longitude', '>', $lon1);
        }
        $n_records = $toponyms->count();        
        $toponyms = $toponyms->orderBy('latitude', 'desc')->get()->groupBy(['latitude', 'longitude']);
        
//dd($toponyms);        
        $objs = [];
        foreach ($toponyms as $lat => $long_list) {
            foreach ($long_list as $lon => $toponym_list) {
                $popup = [];
                foreach ($toponym_list as $toponym) {
                   $popup[] = to_show($toponym->name, 'toponym', $toponym); 
                }
            }
            $objs[] = ['lat'=>$lat, 'lon'=>$lon, 'popup' => join('<br>', $popup)]; 
        }
//dd($objs);        
        return view('dict.toponyms.shaidomozero', 
                compact('objs', 'n_records', 'args_by_get', 'url_args' ));
    }
    
    public function nLadoga()
    {
        $url_args = $this->url_args;
        $url_args['search_districts'] = Toponym::nLadogaDistricts;
        $args_by_get = search_values_by_URL($url_args);

        $toponyms = Toponym::search($url_args);
        $n_records = $toponyms->count();        
        $toponyms = $toponyms->paginate($this->url_args['portion']);
        
        $district_values = District::getList();
        $geotype_values = Geotype::getList();
        $region_values = Region::getList();
        $settlement_values = Settlement::getList();
        $sort_values = Toponym::sortList();

        return view('dict.toponyms.nladoga', 
                compact('district_values', 'geotype_values', 'region_values', 
                        'settlement_values', 'sort_values',
                        'toponyms', 'n_records', 'args_by_get', 'url_args' ));
    }
    
    public function nladogaOnMap()
    {
        $url_args = $this->url_args;
        $url_args['search_districts'] = Toponym::nLadogaDistricts;
        if (empty($url_args['map_height'])) {
            $url_args['map_height'] = 1000;
        }
        $args_by_get = search_values_by_URL($url_args);
        $limit = 3000;
        

        list($total_rec, $show_count, $objs, $limit, $bounds, $url_args) 
                = Toponym::forMap($limit, $url_args);                  
//dd($total_rec);
        $district_values = District::getList();
        $geotype_values = Geotype::getList();
        $settlement_values = Settlement::getList();
        $sort_values = Toponym::sortList();

        return view('dict.toponyms.nladoga_on_map', 
                compact('bounds', 'district_values', 'objs', 'limit',
                        'geotype_values', 'show_count',
                        'settlement_values', 'sort_values', 
                        'total_rec', 'args_by_get', 'url_args' ));
    }
    
    public function duplicates(Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        $group_by = array_merge(['name', 'geotype_id'], (array)$request->input('group_by'));//[], 'district_id', 'settlement1926_id'];
        if (in_array('coordinates', $group_by)) {
            $group_by[] = 'longitude';
        }
        $group_by_fields = collect(['district_id'=>'Современный район', 
            'settlement1926_id'=>'Поселение нач. XX века',
            'latitude'=>trans('toponym.coordinates'),
            'wd'=>trans('toponym.wd'),
            'caseform'=>trans('toponym.caseform'),
            'etymology'=>trans('toponym.etymology'),
            'etymology_nation_id'=>trans('misc.etymology_nation'),
            'ethnos_territory_id'=>trans('misc.ethnos_territory'),
            'ethnos_territory_id'=>trans('misc.ethnos_territory'),
            'main_info'=>trans('toponym.main_info'),
            'legend'=>trans('toponym.legend'),
            ]);
        $check_by = (array)$request->input('check_by');
        $check_by_fields = [
            'updated' => 'Дата создания совпадает, а дата обновления отличается от даты создания не больше, чем на минуту'
            //'struct'=>trans('misc.struct')
            ];
//dd($group_by);
        $toponyms = Toponym::groupBy($group_by)
                  ->havingRaw('count(*) > 1')->get($group_by);
//dd($toponyms);        
        $ids=[];
        foreach ($toponyms as $toponym) {
            $dubles = Toponym::where('id','>',0);
            foreach ($group_by as $field) {
                $dubles->where($field, $toponym->{$field});
            }
//dd($dubles->get());   
/*            if (!$dubles->count()) {
                continue;
            }*/
            if (!empty($check_by['updated'])) {
                $dubles->whereRaw('TIMESTAMPDIFF(SECOND, created_at, updated_at) < 60'); 
            }
            foreach ($dubles->get('id') as $duble) {
                $ids[] = $duble->id;
            }            
        }
//dd($ids);
        $toponyms = Toponym::search($url_args)->whereIn('id', $ids);        
        $n_records = $toponyms->count();  
//dd($n_records);        
        $toponyms = $toponyms->paginate($this->url_args['portion']);
        
        $district_values = District::getList();
        $district1926_values = District1926::getList();
        $geotype_values = Geotype::getList();
        $region_values = Region::getList();
        $selsovet1926_values = Selsovet1926::getList();
        $settlement_values = Settlement::getList();
        $settlement1926_values = Settlement1926::getList();
        $sort_values = Toponym::sortList();

        return view('dict.toponyms.duplicates', 
                compact('check_by_fields', 'district_values', 'district1926_values', 'geotype_values', 
                        'group_by_fields', 'region_values', 'selsovet1926_values', 
                        'settlement_values', 'settlement1926_values', 'sort_values', 
                        'toponyms', 'n_records', 'args_by_get', 'url_args' ));
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
        $ethnos_territory_value = (int)$request->input('ethnos_territory_id');
        $etymology_nation_values = [''=>NULL] + EtymologyNation::getList();
        $geotype_values = [''=>NULL] + Geotype::getList();
        $informant_values = Informant::getList();
        $recorder_values = Recorder::getList();
        
        $struct_values = [''=>NULL] + Struct::getList();
        $structhier_values = Structhier::getGroupedList();
        $type_values = [''=>NULL] + Settlement::getTypeList();
        $lang_values = [''=>NULL] + Lang::getList();
        $source_values = [''=>NULL] + Source::getList(true);

        $event_ids = (array)$request->input('event_id');
        $event_value = Event::whereIn('id', $event_ids)->get();
        
        for ($i=0; $i<4; $i++) {
            $structs[]=[];
            $structhiers[]=[];            
        }
        
        $action='create';

        return view('dict.toponyms.modify', 
                compact('action', 'district_value', 'district_values', 'district1926_value', 
                        'district1926_values', 'ethnos_territory_value', 'ethnos_territory_values', 
                        'etymology_nation_values', 'event_value', 'geotype_values',  
                        'informant_values', 'lang_values', 'recorder_values', 'region_value',
                        'region_values', 'region1926_value', 'selsovet1926_value', 
                        'selsovet1926_values', 'settlement_value', 
                        'settlement_values', 'settlement1926_value', 
                        'settlement1926_values', 'source_values', 'structs', 
                        'structhiers', 'struct_values', 'structhier_values', 
                        'type_values', 'args_by_get', 'url_args'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name'  => 'required|max:255',
//            'wd'    => 'integer'
            ]);
        
        // todo: validate wd starts from 'Q', contains only numbers.
        
        $data = $request->only('name', 'district_id', 'settlement1926_id', 'lang_id',
                    'geotype_id', 'etymology', 'etymology_nation_id', 'legend',
                    'ethnos_territory_id', 'caseform', 'main_info', 'folk', 'wd', 
                    'latitude', 'longitude', 'text_ids');
        
        $data['name'] = to_right_form($data['name']);
        
        // Wikidata ID Qddd -> ddd or ddd -> ddd
        if(preg_match("/^Q(\d+)$/", $data['wd'], $regs)){
            $data['wd'] = $regs[1];
        }
        if ($data['latitude']==0) {
            $data['latitude'] = null;
        }
        if ($data['longitude']==0) {
            $data['longitude'] = null;
        }
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
        $lang_values = [''=>NULL] + Lang::getList();
        $source_values = [''=>NULL] + Source::getList(true);
        
        $structs = $structhiers = [];
        foreach ($toponym->structs as $struct) {
            $structs[]=[$struct->id];
            $structhiers[]=[$struct->structhier_id];
        }
        for ($i=sizeof($toponym->structs); $i<4; $i++) {
            $structs[]=[];
            $structhiers[]=[];            
        }
        
        $action='edit';

        return view('dict.toponyms.modify', 
                compact('action', 'district_values', 'district1926_values', 
                        'ethnos_territory_values', 'etymology_nation_values', 
                        'geotype_values',  'informant_values', 'lang_values', 'recorder_values', 
                        'region_values', 'selsovet1926_values', 'settlement_values', 
                        'settlement1926_values', 'source_values', 'structs', 
                        'structhiers', 'struct_values', 'structhier_values', 
                        'toponym', 'type_values', 'args_by_get', 'url_args'));
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
//dd($this->validateRequest($request));        
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
    public function destroy(int $id, Request $request)
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
        
        $back_route = $request->input('back_route');
        
        if ($error) {
                return Redirect::to(route('toponyms.'.($back_route ?? 'index')).($this->args_by_get))
                               ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('toponyms.'.($back_route ?? 'index')).($this->args_by_get))
                  ->withSuccess($result['message']);
        }
    }
    
    public function linkToSettlement() {
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
        $source_values = [''=>NULL] + Source::getList(true);

        return view('dict.toponyms.link_to_settl', 
                compact('district_values', 'district1926_values', 
                        'ethnos_territory_values', 'etymology_nation_values', 
                        'geotype_values', 'informant_values', 'recorder_values',
                        'region_values', 'selsovet1926_values', 
                        'settlement_values', 'settlement1926_values', 'sort_values', 
                        'source_values', 'struct_values', 'structhier_values', 
                        'toponyms', 'n_records', 'args_by_get', 'url_args' ));
    }
    
    public function linkToSettlementSave(Request $request) {
        $url_args = $this->url_args;
        $top_ids = $request->toponyms;
        $copy_coords = $request->copy_coords;
        if (empty($top_ids)) {
            return;
        }
        $toponyms = Toponym::whereIn('id', $top_ids)->get();

        foreach ($toponyms as $toponym) {
            if ($url_args['settlement_link']) {
                $toponym->settlements()->sync([$url_args['settlement_link']]);
                $settlement = Settlement::find($url_args['settlement_link']);
                if (!$url_args['district_link']) {
                    if ($settlement && $settlement->districts()->first()) {
                        $toponym->district_id = $settlement->districts()->first()->id;
                    }
                }
                if (!empty($copy_coords) && $settlement) {
                    $toponym->latitude = $settlement->latitude;
                    $toponym->longitude = $settlement->longitude;
                }
            }
            if ($url_args['district_link']) {
                $toponym->district_id = $url_args['district_link'];
            }
            $toponym->save();
        }
        return Redirect::to(route('toponyms.link_to_settl').($this->args_by_get))
                       ->withSuccess(\Lang::get('messages.updated_success'));        
    }
    
    public function listForExport() {
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
        $source_values = [''=>NULL] + Source::getList(true);

        return view('dict.toponyms.list_for_export', 
                compact('district_values', 'district1926_values', 
                        'ethnos_territory_values', 'etymology_nation_values', 
                        'geotype_values', 'informant_values', 'recorder_values',
                        'region_values', 'selsovet1926_values', 
                        'settlement_values', 'settlement1926_values', 'sort_values', 
                        'source_values', 'struct_values', 'structhier_values', 
                        'toponyms', 'n_records', 'args_by_get', 'url_args' ));
    }
    
    public function export(Request $request) {
        $url_args = $this->url_args;
        $top_ids = $request->toponyms;
        if (empty($top_ids)) {
            return;
        }
        $with_district = $request->with_district;
        $with_settlement = $request->with_settlement;
        $sort_by = $request->sort_by;
        $toponyms = Toponym::whereIn('id', $top_ids)->orderBy($url_args['sort_by'])->get();
                
        $dir = env('TMP_DIR');
        Settings::setTempDir(env('TMP_DIR'));
        $filename = $dir."/toponyms".date('Y-m-d-H-i').".docx";
        $phpWord = new PhpWord();

        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);
        $secStyle = ['orientation'=>'landscape',
                     'pageNumberingStart' => 1,
		     'borderBottomSize'=>100,
		     'borderBottomColor'=>'C0C0C0'];
        $tableStyle = [
            'borderColor' => '000000',
            'borderSize'  => 6,
            'cellMargin'  => 100,
            'unit' => TblWidth::TWIP
        ];
        $headStyle = ['align' => 'center', 'spaceAfter' => '18'];
        $fParStyle = ['align' => 'center', 'valign' => 'center'];
        $parStyle = ['valign' => 'top'];
        $fRowFontStyle = ['bold' => true];
/*        $fRowFontStyle = ['bold' => true, 'size' => 12];
        $rowFontStyle = ['size' => 12];*/
//dd($url_args['search_district']);        
        $section = $phpWord->addSection($secStyle);
        if (!empty($url_args['search_districts'])) {
            $textrun = $section->addTextRun($headStyle);
            $textrun->addText(join(',', District::whereIn('id', $url_args['search_districts'])->pluck('name_ru')->toArray()), ['bold' => true, 'size' => 18]);
        }
        if (!empty($url_args['search_districts1926'])) {
            $textrun = $section->addTextRun($headStyle);
            $textrun->addText('/'.join(',', District1926::whereIn('id', $url_args['search_districts1926'])->pluck('name_ru')->toArray()), ['bold' => true, 'size' => 18], $headStyle);
        }
        if (!empty($url_args['search_settlements'])) {
            $textrun = $section->addTextRun($headStyle);
            $textrun->addText(join(',', Settlement::whereIn('id', $url_args['search_settlements'])->pluck('name_ru')->toArray()), ['bold' => true, 'size' => 14], $headStyle);
        }
        if (!empty($url_args['search_settlements1926'])) {
            $textrun = $section->addTextRun($headStyle);
            $textrun->addText(join(',', Settlement1926::whereIn('id', $url_args['search_settlements1926'])->pluck('name_ru')->toArray()), ['bold' => true, 'size' => 14], $headStyle);
        }
        if ($with_district && $with_settlement) {
            $c1 = 1900;
            $c2 = 1900;
            $c3 = 3000;
            $c4 = 1000;
            $c5 = 4000;
            $c6 = 2200;
        } elseif ($with_district || $with_settlement) {
            $c1 = 1900;
            $c2 = 1900;
            $c3 = 3000;
            $c4 = 1000;
            $c5 = 5900;
            $c6 = 2200;
        } else {
            $c3 = 3000;
            $c4 = 1000;
            $c5 = 7800;
            $c6 = 2200;
        }        
        $table = $section->addTable($tableStyle);
        $table->addRow();
        if ($with_district) {
            $cell = $table->addCell($c1);
            $textrun = $cell->addTextRun($fParStyle);
            $textrun->addText(trans('toponym.district'). ' /', $fRowFontStyle);
            $textrun->addTextBreak();
            $textrun->addText(trans('toponym.district1926'), $fRowFontStyle);
        }
        if ($with_settlement) {
            $cell = $table->addCell($c2);
            $textrun = $cell->addTextRun($fParStyle);
            $textrun->addText(trans('toponym.settlement'). ' /', $fRowFontStyle);
            $textrun->addTextBreak();
            $textrun->addText(trans('toponym.settlement1926'), $fRowFontStyle);
        }
        $table->addCell($c3)->addText('Заголовочное слово', $fRowFontStyle);
        $table->addCell($c4)->addText(trans('misc.geotype'), $fRowFontStyle);
        $table->addCell($c5)->addText(trans('toponym.main_info'), $fRowFontStyle);
        $table->addCell($c6)->addText(trans('toponym.legend'), $fRowFontStyle);
        
        foreach ($toponyms as $r) {
            $table->addRow();
            
            if ($with_district) {
                $cell = $table->addCell($c1);
                $textrun = $cell->addTextRun($parStyle);
                $textrun->addText($r->district_name . ($r->district1926_name ? ' / '. $r->district1926_name : ''));
            }
            if ($with_settlement) {
                $cell = $table->addCell($c2);
                $textrun = $cell->addTextRun($parStyle);
                $textrun->addText($r->settlement_name . ($r->settlement1926_name ? ' / '. $r->settlement1926_name : ''));
            }            
            $table->addCell($c3)->addText($r->name. ($r->topnames()->count() ? ' ('. join(', ', $r->topnames()->pluck('name')->toArray()).')' : ''), $fRowFontStyle);
            $table->addCell($c4)->addText($r->geotype ? $r->geotype->name : '');
            $table->addCell($c5)->addText(preg_replace("/\n/", " ",$r->main_info));
            $table->addCell($c6)->addText(preg_replace("/\n/", " ",$r->legend));
        }
        
        $phpWord ->save($filename); 

        return response()->download($filename)
                         ->deleteFileAfterSend(true);
        
/*        return response()->download($objWriter)
                ->deleteFileAfterSend(true);*/

/*        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Content-type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename='.$filename,
                'Expires'             => '0',
                'Pragma'              => 'public'
        ];

        $callback = function() use ($toponyms) {
            $FH = fopen('php://output', 'w');
            
            fputcsv($FH, [trans('toponym.district'). ' / '. trans('toponym.district1926'),
                              trans('toponym.settlement'). ' / '. trans('toponym.settlement1926'),
                              'Заголовочное слово',
                              trans('misc.geotype'),
                              trans('toponym.main_info'),
                              trans('toponym.legend')]);
            
            foreach ($toponyms as $r) {
                fputcsv($FH, [
                    $r->district_name . ($r->district1926_name ? ' / '. $r->district1926_name : ''),
                    $r->settlement_name . ($r->settlement1926_name ? ' / '. $r->settlement1926_name : ''),
                    $r->name. ($r->topnames()->count() ? join(', ', $r->topnames()->pluck('name')->toArray()) : ''),
                    $r->geotype ? $r->geotype->name : '',
                ]);
            }
        
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
        
/*
    // the csv file with the first row
    $output = implode(",", [trans('toponym.district'). ' / '. trans('toponym.district1926'),
                              trans('toponym.settlement'). ' / '. trans('toponym.settlement1926'),
                              'Заголовочное слово',
                              trans('misc.geotype'),
                              trans('toponym.main_info'),
                              trans('toponym.legend')])."\n";

    foreach ($toponyms as $r) {
        // iterate over each tweet and add it to the csv
        $output .=  implode(",",[
                    $r->district_name . ($r->district1926_name ? ' / '. $r->district1926_name : ''),
                    $r->settlement_name . ($r->settlement1926_name ? ' / '. $r->settlement1926_name : ''),
                    $r->name. ($r->topnames()->count() ? join(', ', $r->topnames()->pluck('name')->toArray()) : ''),
                    $r->geotype ? $r->geotype->name : '',
                ])."\n"; // append each row
    }

    // headers used to make the file "downloadable", we set them manually
    // since we can't use Laravel's Response::download() function
    $headers = array(
        'Content-Type' => 'text/csv; charset=windows-1251',
        'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        );

    // our response, this will be equivalent to your download() but
    // without using a local file
    return Response::make(mb_convert_encoding(rtrim($output, "\n"), "utf-8", "windows-1251"), 200, $headers);*/
    }
    
}

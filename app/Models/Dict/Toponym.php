<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Settlement1926;
use App\Models\Aux\Geotype;
use App\Models\Aux\EtymologyNation;
use App\Models\Aux\EthnosTerritory;
use App\Models\Aux\Struct;

class Toponym extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'district_id', 'SETTLEMENT', 'settlement1926_id', 
                           'geotype_id', 'etymology', 'etymology_nation_id', 'ethnos_territory_id', 
                           'caseform'];
    public $timestamps = false;
    const SortList=['name', 'id'
//        2 => 'created_at'
    ];
    //use \App\Traits\Methods\getNameAttribute;
    
    
    /**
     * Get the settlement1926 which contains this toponym
     * One To Many (Inverse) / Belongs To.
     * One settlement1926 and many toponyms.
     * https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function settlement1926()
    {
        return $this->belongsTo(Settlement1926::class);
    }
    
    /**
     * Get the district which contains this toponym
     * One To Many (Inverse) / Belongs To, https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function district()
    {
        //                                       'foreign_key', 'owner_key'
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
    
    /**
     * Get the geotype which contains this toponym
     * One To Many (Inverse) / Belongs To
     */
    public function geotype()
    {
        //                                      'foreign_key','owner_key'
        return $this->belongsTo(Geotype::class, 'geotype_id', 'id');
    }
    
    /**
     * Get the etymology nation name which contains this toponym
     * One To Many (Inverse) / Belongs To
     */
    public function etymologyNation()
    {
        return $this->belongsTo(EtymologyNation::class);
    }
    
    /**
     * Get the geotype which contains this toponym
     * One To Many (Inverse) / Belongs To
     */
    public function ethnosTerritory()
    {
        return $this->belongsTo(EthnosTerritory::class);
    }
    
    /**
     * The structures that belong to the toponym. (many to many relation)
     */
    public function structs()
    {
        return $this->belongsToMany(Struct::class);
    }
    
    
    
    /**
     * Get 'Region, district, SETTLEMENT (String)' concatenated by comma.
     */
    public function getLocationAttribute()
    {
        return $this->getRegionNameAttribute().', '. 
               $this->getDistrictNameAttribute().', '. 
               $this->getSettlementNameAttribute();
    }
    
    public function getRegionNameAttribute()
    {

        return optional(optional($this->district)->region)->name; 
    }
    
    public function getDistrictNameAttribute()
    {
        if( $this->district ) 
        { 
            return $this->district->name; 
        }
        return "";
    }
    
    public function getSettlementNameAttribute()
    {
        // TODO: to change string to settlement_id,
        // to create tables (pink colors): settlements
        return $this->SETTLEMENT;
    }
    
    /**
     * Get 'Region, district1926, selsovet1926, settlement1926' concatenated by comma.
     */
    public function getLocation1926Attribute()
    {
        return $this->getRegion1926NameAttribute().', '. 
               $this->getDistrict1926NameAttribute().', '. 
               $this->getSelsovet1926NameAttribute().', '.
               $this->getSettlement1926NameAttribute();
    }
    
    public function getSelsovet1926IdAttribute()
    {
        return $this->settlement1926 
                    ? $this->settlement1926->selsovet_id : NULL;
    }

    public function getDistrict1926IdAttribute()
    {
        return $this->settlement1926 && $this->settlement1926->selsovet1926
                    ? $this->settlement1926->selsovet1926->district1926_id : NULL;
    }

    public function getRegion1926IdAttribute()
    {
        return $this->settlement1926 && $this->settlement1926->selsovet1926  
                        && $this->settlement1926->selsovet1926->district1926
                    ? $this->settlement1926->selsovet1926->district1926->region_id : NULL;
    }
    
    public function getRegionIdAttribute()
    {
        return $this->district ? $this->district->region_id : NULL;
    }
    /**
     * Get name of region via selsovet1926->district1926.
     * If name is absent, then return empty string.
     */
    public function getRegion1926NameAttribute()
    {
        if( $this->settlement1926 &&
            $this->settlement1926->selsovet1926 &&
            $this->settlement1926->selsovet1926->district1926 &&
            $this->settlement1926->selsovet1926->district1926->region ) 
        { 
            return $this->settlement1926->selsovet1926->district1926->region->name; 
        }
        
        return "";
    }
    
    public function getDistrict1926NameAttribute()
    {
        if( $this->settlement1926 &&
            $this->settlement1926->selsovet1926 &&
            $this->settlement1926->selsovet1926->district1926 ) 
        { 
            return $this->settlement1926->selsovet1926->district1926->name; 
        }
        
        return "";
    }
    
    // selsovet1926->name
    public function getSelsovet1926NameAttribute()
    {
        if( $this->settlement1926 &&
            $this->settlement1926->selsovet1926 ) 
        { 
            return $this->settlement1926->selsovet1926->name; 
        }
        
        return "";
    }
    
    public function getSettlement1926NameAttribute()
    {
        return $this->settlement1926 ? $this->settlement1926->name : '';
    }
    
    public function geotypeValue()
    {
        return $this->geotype_id ? [$this->geotype_id] : [];
    }
    
    public function ethnosTerritoryValue()
    {
        return $this->ethnos_territory_id ? [$this->ethnos_territory_id] : [];
    }
    
    public function etymologyNationValue()
    {
        return $this->etymology_nation_id ? [$this->etymology_nation_id] : [];
    }
    
    public function regionValue()
    {
        return $this->region_id ? [$this->region_id] : [];
    }
    
    public function region1926Value()
    {
        return $this->region1926_id ? [$this->region1926_id] : [];
    }
    
    public function districtValue()
    {
        return $this->district_id ? [$this->district_id] : [];
    }
    
    public function district1926Value()
    {
        return $this->district1926_id ? [$this->district1926_id] : [];
    }
    
    public function selsovet1926Value()
    {
        return $this->selsovet1926_id ? [$this->selsovet1926_id] : [];
    }
    
    public function settlement1926Value()
    {
        return $this->settlement1926_id ? [$this->settlement1926_id] : '';
    }
    
    /** Gets array of search parameters.
     * 
     * @param type $request
     * @return type
     */
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'in_desc'     => (int)$request->input('in_desc'),
                    'search_districts'   => (array)$request->input('search_districts'),
                    'search_districts1926'   => (array)$request->input('search_districts1926'),
                    'search_ethnos_territories'   => (array)$request->input('search_ethnos_territories'),
                    'search_etymology_nations'   => (array)$request->input('search_etymology_nations'),
                    'search_geotypes'    => (array)$request->input('search_geotypes'),
                    'search_regions'     => (array)$request->input('search_regions'),
                    'search_regions1926'     => (array)$request->input('search_regions1926'),
                    'search_selsovets1926' => (array)$request->input('search_selsovets1926'),
                    'search_settlement' => $request->input('search_settlement'),
                    'search_settlements1926' => (array)$request->input('search_settlements1926'),
                    'search_structs'    => (array)$request->input('search_structs'),
                    'search_structhiers'    => (array)$request->input('search_structhiers'),
                    'search_toponym'    => $request->input('search_toponym'),
                    'sort_by' => $request->input('sort_by'),
                ];
        $sort_list = self::SortList;

//dd($sort_list, $url_args['sort_by']);        
        if (!in_array($url_args['sort_by'], $sort_list)) {
            $url_args['sort_by']= $sort_list[0];
        }
        return $url_args;
    }
    
    
    /** Search toponym by various parameters. 
     * 
     * @param array $url_args
     * @return type
     */
    public static function search(Array $url_args) {
        
        $toponyms = self::orderBy($url_args['sort_by'], $url_args['in_desc'] ? 'DESC' : 'ASC');
        //$toponyms = self::searchByPlace($toponyms, $url_args['search_place'], $url_args['search_district'], $url_args['search_region']);
        
        $toponyms = self::searchByRegion($toponyms, $url_args['search_regions']);
        $toponyms = self::searchByLocation1926($toponyms, $url_args['search_selsovets1926'], $url_args['search_districts1926'], $url_args['search_regions1926']);
        $toponyms = self::searchByStruct($toponyms, $url_args['search_structs'], $url_args['search_structhiers']);
        
        if ($url_args['search_toponym']) {
            $toponyms = $toponyms->where('name','LIKE',$url_args['search_toponym']);
        }         
        if ($url_args['search_settlement']) {
            $toponyms = $toponyms->where('SETTLEMENT','LIKE',$url_args['search_settlement']);
        }        
        if ($url_args['search_geotypes']) {
            $toponyms = $toponyms->whereIn('geotype_id',$url_args['search_geotypes']);
        }         
        if ($url_args['search_districts']) {
            $toponyms = $toponyms->whereIn('district_id',$url_args['search_districts']);
        }         
        if ($url_args['search_settlements1926']) {
            $toponyms = $toponyms->whereIn('settlement1926_id',$url_args['search_settlements1926']);
        } 
        if ($url_args['search_ethnos_territories']) {
            $toponyms = $toponyms->whereIn('ethnos_territory_id',$url_args['search_ethnos_territories']);
        }         
        if ($url_args['search_etymology_nations']) {
            $toponyms = $toponyms->whereIn('etymology_nation_id',$url_args['search_etymology_nations']);
        }         
//dd($toponyms->toSql());                                
//dd(to_sql($toponyms));
        return $toponyms;
    }
    
    /** Search toponym by region. 
     */
    public static function searchByRegion($toponyms, $search_regions) {
        
        if(!sizeof($search_regions)) {
            return $toponyms;
        }
        
        $toponyms = $toponyms->whereIn('district_id', function($query) use ($search_regions) {
            $query -> select ('id') -> from ('districts') 
                    -> whereIn('region_id', $search_regions );
        });
        
//dd($toponyms->toSql());                                

        return $toponyms;
    }
    
    public static function searchByLocation1926($toponyms, $search_selsovets1926, $search_districts1926, $search_regions1926) {
        
        if(!sizeof($search_selsovets1926) && !sizeof($search_districts1926) && !sizeof($search_regions1926)) {
            return $toponyms;
        }
        
        $toponyms = $toponyms->whereIn('settlement1926_id', function($q1) use ($search_selsovets1926, $search_districts1926, $search_regions1926) {
            $q1->select('id')->from('settlements1926');
            if (sizeof($search_selsovets1926)) {
                $q1->whereIn('selsovet_id', $search_selsovets1926);
            }
            if (sizeof($search_districts1926) || sizeof($search_regions1926)) {
                $q1->whereIn('selsovet_id', function ($q2) use ($search_districts1926, $search_regions1926) {
                    $q2->select('id')->from('selsovets1926');
                    if (sizeof($search_districts1926)) {
                        $q2->whereIn('district1926_id', $search_districts1926);                        
                    }
                    if (sizeof($search_regions1926)) {
                        $q2->whereIn('district1926_id', function ($q3) use ($search_regions1926) {
                            $q3->select('id')->from('districts1926')
                               ->whereIn('region_id', $search_regions1926);
                        });                        
                        
                    }
                });
            }
        });        
//dd($toponyms->toSql());                                
        return $toponyms;
    }
    
    public static function searchByStruct($toponyms, $search_structs, $search_structhiers) {
        
        if(!sizeof($search_structs) && !$search_structhiers) {
            return $toponyms;
        }
        
        $toponyms = $toponyms->whereIn('id', function($q1) use ($search_structs, $search_structhiers) {
            $q1->select('toponym_id')->from('struct_toponym');
            if (sizeof($search_structs)) {
                $q1->whereIn('struct_id', $search_structs);
            }
            if ($search_structhiers) {
                $q1->whereIn('struct_id', function ($q2) use ($search_structhiers) {
                    $q2->select('id')->from('structs');
                    if ($search_structhiers) {
                        $q2->whereIn('structhier_id', $search_structhiers);                        
                    }
                });
            }
        });
        return $toponyms;
    }
    
    public static function sortList() {
        $list = [];
        foreach (self::SortList as $field) {
            $list[$field] = \Lang::get('messages.sort'). ' '. \Lang::get('toponym.by_'.$field);
        }
        return $list;
    }
}

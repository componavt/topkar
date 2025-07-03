<?php namespace App\Traits\Search;

trait SettlementSearch
{
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'in_desc'     => (int)$request->input('in_desc'),
                    'search_districts'   => (array)$request->input('search_districts'),
                    'search_id'       => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_name'     => $request->input('search_name'),
                    'search_regions'     => (array)$request->input('search_regions'),
                    'sort_by' => $request->input('sort_by'),
                ];
        
        $sort_list = self::SortList;
        if (!in_array($url_args['sort_by'], $sort_list)) {
            $url_args['sort_by']= $sort_list[0];
        }
        return remove_empty_elems($url_args);
    }
    
    public static function search(Array $url_args) {
        $settlements = self::orderBy($url_args['sort_by'], $url_args['in_desc'] ? 'DESC' : 'ASC');
        $settlements = self::searchByLocation($settlements, $url_args['search_regions'], $url_args['search_districts']);
        $settlements = self::searchByID($settlements, $url_args['search_id']);
        $settlements = self::searchByName($settlements, $url_args['search_name']);
//dd($places->toSql());                                
        return $settlements;
    }
    
    public static function searchByName($places, $place_name) {
        if (!$place_name) {
            return $places;
        }
        return $places->where(function($q) use ($place_name){
                            $q->where('name_ru','like', $place_name)
                              ->orWhere('name_krl','like', $place_name)            
                              ->orWhere('name_vep','like', $place_name)            
                              ->orWhere('name_en','like', $place_name);           
                });
    }
    
    public static function searchByLocation($settlements, $regions, $districts) {
        
        if(!sizeof($regions) && !sizeof($districts)) {
            return $settlements;
        }
        
        return $settlements->whereIn('id', function($q) use ($regions, $districts) {
                        $q->select('settlement_id')->from('district_settlement');
                        if (sizeof($districts)) {
                            $q->whereIn('district_id', $districts);
                        }
                        if (sizeof($regions)) {
                            $q->whereIn('district_id', function($query) use ($regions) {
                              $query -> select ('id') -> from ('districts') 
                                      -> whereIn('region_id', $regions );
                            });
                        }
                });
    }
    
    public static function searchByID($places, $search_id) {
        if (!$search_id) {
            return $places;
        }
        return $places->where('id',$search_id);
    }
    
    
}
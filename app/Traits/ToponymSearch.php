<?php namespace App\Traits;

use App\Models\Dict\Toponym;

trait ToponymSearch
{
    use \App\Traits\Methods\search\byName;
    
    /** Gets array of search parameters.
     * 
     * @param type $request
     * @return type
     */
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'check_by'   => (array)$request->input('check_by'),
                    'not_claster'   => (int)$request->input('not_claster'),
                    'created_at'     => $request->input('created_at'),
                    'district_link'     => (int)$request->input('district_link'),
                    'group_by'   => (array)$request->input('group_by'),
                    'in_desc'     => (int)$request->input('in_desc'),
                    'map_height' => (int)$request->input('map_height'),
                    'max_lat'    => (float)$request->input('max_lat'),
                    'max_lon'    => (float)$request->input('max_lon'),
                    'min_lat'    => (float)$request->input('min_lat'),
                    'min_lon'    => (float)$request->input('min_lon'),
                    'only_exact_coords' => (int)$request->input('only_exact_coords'),
                    'outside_bounds' => (int)$request->input('outside_bounds'),
                    'popup_all' => (int)$request->input('popup_all'),
                    'region_link'     => (int)$request->input('region_link'),
                    'search_districts'   => (array)$request->input('search_districts'),
                    'search_districts1926'   => (array)$request->input('search_districts1926'),
                    'search_ethnos_territories'   => (array)$request->input('search_ethnos_territories'),
                    'search_etymology_nations'   => (array)$request->input('search_etymology_nations'),
                    'search_geotypes'    => (array)$request->input('search_geotypes'),
                    'search_informants'    => (array)$request->input('search_informants'),
                    'search_lang'    => (int)$request->input('search_lang'),
                    'search_record_places' => (array)$request->input('search_record_places'),
                    'search_record_places1926' => (array)$request->input('search_record_places1926'),
                    'search_recorders'    => (array)$request->input('search_recorders'),
                    'search_regions'     => (array)$request->input('search_regions'),
                    'search_regions1926'     => (array)$request->input('search_regions1926'),
                    'search_selsovets1926' => (array)$request->input('search_selsovets1926'),
                    'search_settlements' => (array)$request->input('search_settlements'),
                    'search_settlements1926' => (array)$request->input('search_settlements1926'),
                    'search_sources'    => (array)$request->input('search_sources'),
                    'search_source_text'    => $request->input('search_source_text'),
                    'search_structs'    => (array)$request->input('search_structs'),
                    'search_structhiers'    => (array)$request->input('search_structhiers'),
                    'search_toponym'    => $request->input('search_toponym'),
                    'settlement_link'     => (int)$request->input('settlement_link'),
                    'sort_by' => $request->input('sort_by'),
                ];
        $sort_list = self::SortList;
        if (!in_array($url_args['sort_by'], $sort_list)) {
            $url_args['sort_by']= $sort_list[0];
        }
        return remove_empty_elems($url_args);
    }
    
    
    /** Search toponym by various parameters. 
     * 
     * @param array $url_args
     * @return type
     */
    public static function search(Array $url_args) {
        $toponyms = self::orderBy($url_args['sort_by'], $url_args['in_desc'] ? 'DESC' : 'ASC');
        //$toponyms = self::searchByPlace($toponyms, $url_args['search_place'], $url_args['search_district'], $url_args['search_region']);
        
        $toponyms = self::searchByNames($toponyms, to_search_form($url_args['search_toponym']));
        $toponyms = self::searchBySettlements($toponyms, $url_args['search_settlements']);
        $toponyms = self::searchByRegion($toponyms, $url_args['search_regions']);
        $toponyms = self::searchByRecordPlace($toponyms, $url_args['search_record_places']);
        $toponyms = self::searchByRecordPlace1926($toponyms, $url_args['search_record_places1926']);
        $toponyms = self::searchByLocation1926($toponyms, $url_args['search_selsovets1926'], $url_args['search_districts1926'], $url_args['search_regions1926']);
        $toponyms = self::searchByStruct($toponyms, $url_args['search_structs'], $url_args['search_structhiers']);
        $toponyms = self::searchByEvents($toponyms, $url_args['search_informants'], $url_args['search_recorders']);
        $toponyms = self::searchBySources($toponyms, $url_args['search_sources']);
        $toponyms = self::searchBySourceText($toponyms, $url_args['search_source_text']);
    //    $toponyms = self::searchByBounds($toponyms, $url_args); не выбираются топонимы без точных координат
        
        if ($url_args['search_lang']) {
            $toponyms = $toponyms->whereLangId($url_args['search_lang']);
        }        
        if (sizeof($url_args['search_geotypes'])) {
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
        if (!empty($url_args['created_at'])) {
            $toponyms = $toponyms->where('created_at', 'like', $url_args['created_at'].'%');
        }                
        
//dd(to_sql($toponyms));
        return $toponyms;
    }
    
    public static function searchByBounds($toponyms, $url_args) {
        if (!empty($url_args['outside_bounds'])) {
            return $toponyms;
        }
        
        if ($url_args['min_lat']) {
            $toponyms = $toponyms->where('latitude', '>=',$url_args['min_lat']);
        }        
        if ($url_args['max_lat']) {
            $toponyms = $toponyms->where('latitude', '<=',$url_args['max_lat']);
        }        
        if ($url_args['min_lon']) {
            $toponyms = $toponyms->where('longitude', '>=',$url_args['min_lon']);
        }        
        if ($url_args['max_lon']) {
            $toponyms = $toponyms->where('longitude', '<=',$url_args['max_lon']);
        }        
        
        return $toponyms;
    }
    
    public static function searchBySourceText($toponyms, $search_source_text) {
        if (!$search_source_text) {
            return $toponyms;
        }   
        
        return $toponyms->whereIn('id', function ($q) use ($search_source_text){
                            $q->select('toponym_id')->from('source_toponym')
                              ->where('source_text', 'LIKE', $search_source_text);
                        });
    }
    
    public static function searchBySources($toponyms, $search_sources) {
        if(!sizeof($search_sources)) {
            return $toponyms;
        }   
        
        return $toponyms->whereIn('id', function ($q) use ($search_sources){
                            $q->select('toponym_id')->from('source_toponym')
                              ->whereIn('source_id', $search_sources);
                        });
    }
    
    /** Search toponym by names. 
     */
    public static function searchByNames($toponyms, $search_toponym) {
        if (!$search_toponym) {
            return $toponyms;
        }   
        $search_toponym = to_search_form($search_toponym);
        
        return $toponyms->where(function ($q) use ($search_toponym){ 
                            $q->where('name_for_search','LIKE',$search_toponym)
                              ->orWhereIn('id', function ($q2) use ($search_toponym){
                                  $q2->select('toponym_id')->from('topnames')
                                     ->where('name_for_search','LIKE',$search_toponym);
                              });
                        });
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
                })/*->orWhere(function ($q1) use ($search_regions) {
                    $q1-> whereIn('settlement1926_id', function($q2) use ($search_regions) {
                        $q2 -> select ('id') -> from ('settlements1926')
                            -> whereIn('selsovet_id', function($q3) use ($search_regions) {
                                $q3 -> select ('id') -> from ('selsovets1926')
                                    -> whereIn('district1926_id', function($q4) use ($search_regions) {
                                    $q4 -> select ('id') -> from ('districts1926') 
                                        -> whereIn('region_id', $search_regions );
                                    });
                                });
                        });
                })*/;
        
//dd($toponyms->toSql());                                

        return $toponyms;
    }
    
    public static function searchBySettlements($toponyms, $search_settlements) {
        
        if(!sizeof($search_settlements)) {
            return $toponyms;
        }
        
        $toponyms = $toponyms->whereIn('id', function($q1) use ($search_settlements) {
            $q1->select('toponym_id')->from('settlement_toponym')
               ->whereIn('settlement_id', $search_settlements);
        });        
//dd($toponyms->toSql());                                
        return $toponyms;
    }
    
    public static function searchByRecordPlace($toponyms, $search_settlements) {
        
        if(!sizeof($search_settlements)) {
            return $toponyms;
        }
        
        $toponyms = $toponyms->whereIn('id', function($q1) use ($search_settlements) {
            $q1->select('toponym_id')->from('events')
               ->whereIn('id', function($q2) use ($search_settlements) {
                    $q2->select('event_id')->from('event_settlement')
                       ->whereIn('settlement_id', $search_settlements);
                });
            });        
//dd($toponyms->toSql());                                
        return $toponyms;
    }
    
    public static function searchByRecordPlace1926($toponyms, $search_settlements) {
        
        if(!sizeof($search_settlements)) {
            return $toponyms;
        }
        
        $toponyms = $toponyms->whereIn('id', function($q1) use ($search_settlements) {
            $q1->select('toponym_id')->from('events')
               ->whereIn('id', function($q2) use ($search_settlements) {
                    $q2->select('event_id')->from('event_settlement1926')
                       ->whereIn('settlement1926_id', $search_settlements);
                });
            });        
        return $toponyms;
    }
    
    public static function searchByEvents($toponyms, $search_informants, $search_recorders) {
        
        if(!sizeof($search_informants) && !sizeof($search_recorders)) {
            return $toponyms;
        }
        
        $toponyms = $toponyms->whereIn('id', function($q1) use ($search_informants, $search_recorders) {
            $q1->select('toponym_id')->from('events');
            if (sizeof($search_informants)) {
               $q1->whereIn('id', function ($q2) use ($search_informants) {
                    $q2->select('event_id')->from('event_informant')
                       ->whereIn('informant_id', $search_informants);
               });
            }
            if (sizeof($search_recorders)) {
               $q1->whereIn('id', function ($q2) use ($search_recorders) {
                    $q2->select('event_id')->from('event_recorder')
                       ->whereIn('recorder_id', $search_recorders);
               });
            }
        });        
//dd($toponyms->toSql());                                
        return $toponyms;
    }
    
    public static function searchByLocation1926($toponyms, $search_selsovets1926, $search_districts1926, $search_regions1926) 
    {     
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
    
    public static function forMap($limit, $url_args) {
        $toponyms = self::search($url_args);
        $total_rec = $toponyms->count(); 
        if (user_can_edit()) {
            $limit = $total_rec;
        }        
        list($show_count, $objs, $checked_ids) = self::toponymsWithCoordsforMap($toponyms, $limit, $url_args);
//dd($objs);        
        
        if (empty($url_args['only_exact_coords']) && $show_count<$limit) {
            list($show_count, $objs, $checked_ids) 
                    = self::toponymsWithSettl26CoordsforMap($objs, $show_count, $limit, $url_args, $checked_ids);
            
            if ($show_count<$limit) {
                list($show_count, $objs) 
                        = self::toponymsWithSettlCoordsforMap($objs, $show_count, $limit, $url_args, $checked_ids);
            }                          
        }
        arsort($objs);
        
        list ($bounds, $url_args) = self::getBounds($objs, $url_args);
        
        return [$total_rec, $show_count, collect($objs), $limit, $bounds, $url_args];
    }
    
    public static function getBounds($objs, $url_args) {
        $bounds = ['min_lat'=>null, 'min_lon'=>null, 'max_lat'=>null, 'max_lon'=>null];
        foreach (array_keys($objs) as $coord) {
            list($lat, $lon) = explode("_", $coord);
            $lat = (float) $lat;
            $lon = (float) $lon;

            if ($bounds['min_lat'] === null || $lat < $bounds['min_lat']) {
                $bounds['min_lat'] = $lat;
            }
            if ($bounds['max_lat'] === null || $lat > $bounds['max_lat']) {
                $bounds['max_lat'] = $lat;
            }
            if ($bounds['min_lon'] === null || $lon < $bounds['min_lon']) {
                $bounds['min_lon'] = $lon;
            }
            if ($bounds['max_lon'] === null || $lon > $bounds['max_lon']) {
                $bounds['max_lon'] = $lon;
            }
        }
        if (empty($url_args['min_lat'])) {
            $url_args['min_lat'] = $bounds['min_lat'];
        } else {
            $bounds['min_lat'] = $url_args['min_lat'];
        }
        if (empty($url_args['min_lon'])) {
            $url_args['min_lon'] = $bounds['min_lon'];
        } else {
            $bounds['min_lon'] = $url_args['min_lon'];
        }
        if (empty($url_args['max_lat'])) {
            $url_args['max_lat'] = $bounds['max_lat'];
        } else {
            $bounds['max_lat'] = $url_args['max_lat'];
        }
        if (empty($url_args['max_lon'])) {
            $url_args['max_lon'] = $bounds['max_lon'];
        } else {
            $bounds['max_lon'] = $url_args['max_lon'];
        }
        return [$bounds, $url_args];
    }

    public static function toponymsWithCoordsforMap($toponyms, $limit, $url_args) {
        $objs = [];
        $checked_ids = [];
        $toponyms_with_coords = $toponyms->withCoords();
        if (empty($url_args['outside_bounds']) && !empty($url_args['min_lat'])) {
            $toponyms->where('latitude', '>=', $url_args['min_lat']);
        }
        if (empty($url_args['outside_bounds']) && !empty($url_args['min_lon'])) {
            $toponyms->where('longitude', '>=', $url_args['min_lon']);
        }
        if (empty($url_args['outside_bounds']) && !empty($url_args['max_lat'])) {
            $toponyms->where('latitude', '<=', $url_args['max_lat']);
        }
        if (empty($url_args['outside_bounds']) && !empty($url_args['max_lon'])) {
            $toponyms->where('longitude', '<=', $url_args['max_lon']);
        }
                    
        $toponyms_with_coords = $toponyms->take($limit)
                           ->orderBy('name')->get();
//dd(to_sql($toponyms_with_coords));        
        $show_count = sizeof($toponyms_with_coords);

        foreach ($toponyms_with_coords as $toponym) {
            $lat = $toponym->latitude;
            $lon = $toponym->longitude;
            $popup = to_show($toponym->name, 'toponym', $toponym, '', 'important').(!$url_args['popup_all'] && $toponym->geotype ? ' ('.$toponym->geotype->name.')' : ''); 
            if (isset($objs[$lat.'_'.$lon])) {
                $objs[$lat.'_'.$lon]['popup'] .= '<br>'.$popup;
            } else {
                $objs[$lat.'_'.$lon] 
                    = ['lat'=>$lat, 'lon'=>$lon, 'popup'=>$popup, 'color'=>'blue']; 
            }
            $checked_ids[] = $toponym->id;
        }
//dd($objs);      
//dump($show_count.',');        
        return [$show_count, $objs, $checked_ids];
    }
    
    public static function toponymsWithSettl26CoordsforMap($objs, $show_count, $limit, $url_args, $checked_ids) {
        $toponyms = Toponym::search($url_args)
            ->whereNotIn('id', $checked_ids)
            ->whereIn('settlement1926_id', function ($q2) use ($url_args) {
                $q2->select('id')->from('settlements1926')
                   ->whereNotNull('latitude')
                   ->whereNotNull('longitude');
                if (!empty($url_args['search_settlements1926'])) {
                    $q2->whereIn('id', $url_args['search_settlements1926']);
                }
                if (empty($url_args['outside_bounds']) && !empty($url_args['min_lat'])) {
                    $q2->where('latitude', '>=', $url_args['min_lat']);
                }
                if (empty($url_args['outside_bounds']) && !empty($url_args['min_lon'])) {
                    $q2->where('longitude', '>=', $url_args['min_lon']);
                }
                if (empty($url_args['outside_bounds']) && !empty($url_args['max_lat'])) {
                    $q2->where('latitude', '<=', $url_args['max_lat']);
                }
                if (empty($url_args['outside_bounds']) && !empty($url_args['max_lon'])) {
                    $q2->where('longitude', '<=', $url_args['max_lon']);
                }                
            })->take($limit-$show_count)
            ->orderBy('name')->get();
        $show_count += sizeof($toponyms);                      
//dump($show_count.',');        

        foreach ($toponyms as $toponym) {
            $objs = self::setToponymToSettlement($objs, $toponym, $toponym->settlement1926, $url_args);
            $checked_ids[] = $toponym->id;
        }
        return [$show_count, $objs, $checked_ids];
    }

    public static function toponymsWithSettlCoordsforMap($objs, $show_count, $limit, $url_args, $checked_ids) {
        $toponyms = Toponym::search($url_args)
            ->whereNotIn('id', $checked_ids)
            ->whereIn('id', function ($q2) use ($url_args) {
                $q2->select('toponym_id')->from('settlement_toponym')
                   ->whereIn('settlement_id', function ($q3) use ($url_args) {
                        $q3->select('id')->from('settlements')
                           ->whereNotNull('latitude')
                           ->whereNotNull('longitude');
                        if (!empty($url_args['search_settlements'])) {
                            $q3->whereIn('id', $url_args['search_settlements']);
                        }
                        if (empty($url_args['outside_bounds']) && !empty($url_args['min_lat'])) {
                            $q3->where('latitude', '>=', $url_args['min_lat']);
                        }
                        if (empty($url_args['outside_bounds']) && !empty($url_args['min_lon'])) {
                            $q3->where('longitude', '>=', $url_args['min_lon']);
                        }
                        if (empty($url_args['outside_bounds']) && !empty($url_args['max_lat'])) {
                            $q3->where('latitude', '<=', $url_args['max_lat']);
                        }
                        if (empty($url_args['outside_bounds']) && !empty($url_args['max_lon'])) {
                            $q3->where('longitude', '<=', $url_args['max_lon']);
                        }                
                   });
            })->take($limit-$show_count)
            ->orderBy('name')->get();
//dd(to_sql($toponyms));            
//dd($toponyms->count());            
        $show_count += sizeof($toponyms);                                          
//dump($show_count.',');        

        foreach ($toponyms as $toponym) {
            $objs = self::setToponymToSettlement($objs, $toponym, $toponym->settlements()->withCoords()->first(), $url_args);
        }
        return [$show_count, $objs];
    }
    
    public static function setToponymToSettlement($objs, $toponym, $settlement, $url_args) {
        $popup = to_show($toponym->name, 'toponym', $toponym)
                    .(!$url_args['popup_all'] && $toponym->geotype ? ' ('.$toponym->geotype->name.')' : ''); 
        $lat = $settlement->latitude;
        $lon = $settlement->longitude;
        if (isset($objs[$lat.'_'.$lon])) {
            if ($objs[$lat.'_'.$lon]['color'] == 'blue') {
                $objs[$lat.'_'.$lon]['color'] = 'violet';
                $objs[$lat.'_'.$lon]['popup'] .= '<br>';
            } else {
                $objs[$lat.'_'.$lon]['popup'] .= '; ';
            }
            $objs[$lat.'_'.$lon]['popup'] .= $popup;
        } else {
            $objs[$lat.'_'.$lon] 
                = ['lat'=>$lat, 'lon'=>$lon, 'color' => 'grey', 
                   'popup' => '<b>'.$settlement->name.'</b><br>'.$popup]; 
        }   
        return $objs;
    }
}
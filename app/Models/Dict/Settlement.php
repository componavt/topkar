<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\Region;

use App\Models\Misc\Geotype;

class Settlement extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['name_ru', 'name_en', 'name_krl', 'wd', 'latitude', 'longitude', 'geotype_id'];
    const Types=[
        93, // city
        21, // village
        3,  // selo
        91, // poselok
        100, // station
        101, // mestechko
        102, // pgt
        103, // siding
        40, // hamlet
    ];


    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\search\byNameKRL;

    // Belongs To One Relations
    use \App\Traits\Relations\BelongsTo\Geotype;
    
    // Belongs To Many Relations
    use \App\Traits\Relations\BelongsToMany\Districts;
    use \App\Traits\Relations\BelongsToMany\Events;
    use \App\Traits\Relations\BelongsToMany\Toponyms;
    
    public function identifiableName()
    {
        return $this->settlementString();//name;
    }    
    
    public function recordPlaces() {
        $place_id = $this->id;
        return Toponym::whereIn('id', function($q1) use ($place_id) {
                    $q1->select('toponym_id')->from('events')
                       ->whereIn('id', function($q2) use ($place_id) {
                            $q2->select('event_id')->from('event_settlement')
                               ->whereSettlementId($place_id);
                        });
                    });        
    }
    
    public function regions()
    {
        $settlement_id = $this->id;
        return Region::whereIn('id', function ($q1) use ($settlement_id) {
                    $q1->select('region_id')->from('districts')
                       ->whereIn('id', function ($q2) use ($settlement_id) {
                        $q2->select('district_id')->from('district_settlement')
                           ->whereSettlementId($settlement_id);
                       });
                });
    }    
    
    public function regionsToString() {
        $locale = app()->getLocale();
        return join(', ', $this->regions()->pluck('name_'.$locale)->toArray());
    }    
    
    /**
     * Gets IDs of dialects for dialect's form field
     *
     * @return Array
     */
    public function districtValue():Array{
        $value = [];
        if ($this->districts) {
            foreach ($this->districts as $district) {
                $value[] = ['id' => [$district->id],
                    'from'=> $district->pivot->include_from,
                    'to'=> $district->pivot->include_to];
            }
        }
        return $value = collect($value)->sortBy('from')->toArray();
    }

    public function districtsToString() {
        $locale = app()->getLocale();
        return join(', ', $this->districts()->pluck('name_'.$locale)->toArray());
    }    
    
    public function districtListToString() {
        $out = $this->districts->pluck('name')->toArray();
        if (!sizeof($out)) {
            return NULL;
        }
        return join(', ',$out);
    }
    
    public function districtNamesWithDates() {
        $out = [];
        foreach ($this->districts as $district) {
            $from = $district->pivot->include_from;
            $to = $district->pivot->include_to;
            $out[$from] = $district->name.($from || $to ? ' ('.$from.'-'.$to.')' : '');
        }
        ksort($out);
        return join(', ',$out);
    }

    public function saveDistricts(array $districts) {
        $this->districts()->detach();
        
        foreach($districts as $district) {
            if ($district['id']) {
                $this->districts()->attach($district['id'],
                        ['include_from'=>$district['from'], 
                         'include_to'=>$district['to']]);
            }
        }        
    }
    
    public static function getTypeList() {
        $locale = app()->getLocale();
        return Geotype::whereIn('id', self::Types)->orderBy('name_'.$locale)
                      ->pluck('name_'.$locale, 'id')->toArray();
    }

    public static function getListWithDistricts() {     
        $places = self::orderBy('name_ru')->get();
        
        $list = array();
        foreach ($places as $row) {
            $list[$row->id] = $row->toStringWithDistrict();
        }
        
        return $list;         
    }
    
    /**
     * Gets full information about settlement
     * 
     * f.e. "Пондала (Pondal), Бабаевский р-н, Вологодская обл."
     * 
     * @param int $lang_id ID of text language for output translation of settlement title, f.e. Pondal
     * 
     * @return String
     */
    
    public function settlementString($lang_id='')
    {
        $info = [];
        
        if ($this->name) {
            $info[0] = $this->name
                     . ($this->name_krl ? ", ".$this->name_krl : '');
        }
        
        if ($this->district) {
            $info[] = $this->district->name;
        }
        
        if ($this->region) {
            $info[] = $this->region->name;
        }
        
        return join(', ', $info);
    }    
    
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'search_districts'   => (array)$request->input('search_districts'),
                    'search_id'       => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_name'     => $request->input('search_name'),
                    'search_regions'     => (array)$request->input('search_regions'),
                ];
        
        return $url_args;
    }
    
    public static function search(Array $url_args) {
        $settlements = self::orderBy('name_ru'); 

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
    
    
    public function toStringWithDistrict() {
        $info = $this->name;
        
        if ($this->districtNamesWithDates()) {
            $info .= ' ('. $this->districtNamesWithDates(). ')';
        }
        
        return $info;
    }
    
    public static function getNameById($id) {
        $place = self::find($id);
        return $place->name;
    }
 
    public static function namesByIdsToString($ids) {
        $names = [];
        foreach ($ids as $id) {
            $names[] = self::getNameById($id);
        }
        return join(', ', $names);
    }
}

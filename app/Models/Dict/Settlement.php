<?php

namespace App\Models\Dict;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\Region;
use App\Models\Misc\Geotype;

class Settlement extends Model
{
//    use HasFactory;
    use \App\Traits\Search\SettlementSearch;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = false; // Don't remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 999999; // Stop tracking revisions after 999999 changes have been made.
    protected $revisionCreationsEnabled = true; // By default the creation of a new model is not stored as a revision. Only subsequent changes to a model is stored.
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    
    
    public $timestamps = false;
    protected $fillable = ['name_ru', 'name_en', 'name_krl', 'name_vep', 'wd', 'latitude', 'longitude', 'geotype_id'];
    const Types=[
        3,  // selo
        21, // village
        40, // hamlet
        47, // tract
        63, // inhabited place - поселение
        69, // territory
        91, // poselok
        93, // city
        100, // station
        101, // mestechko
        102, // pgt
        103, // siding
        107, // vyselok
        115, // rural settlement
        126, // settlement - н.п.
    ];
    const SortList=['name_ru', 'id'];


    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\wdURL;    
//    use \App\Traits\Methods\search\byNameKRL;
    use \App\Traits\Methods\sortList;
    
    //Scopes
    use \App\Traits\Scopes\WithCoords;

    // Belongs To One Relations
    use \App\Traits\Relations\BelongsTo\Geotype;
    
    // Belongs To Many Relations
    use \App\Traits\Relations\BelongsToMany\Districts;
    use \App\Traits\Relations\BelongsToMany\Events;
    use \App\Traits\Relations\BelongsToMany\Toponyms;
    
    public static function boot()
    {
        parent::boot();
    }
    
    public function identifiableName()
    {
        return $this->settlementString();//name;
    }    
    
    public function hasCoords()
    {
        return ($this->latitude && $this->longitude) ? true : false;
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
//dd($this->districts);        
        foreach ($this->districts as $district) {
            $from = $district->pivot->include_from;
            $to = $district->pivot->include_to;
            $out[$from.'_'.$district->id] = $district->name.($from || $to ? ' ('.$from.'-'.$to.')' : '');
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
    
    public function getSameSettlementsAttribute() {
        if (!$this->hasCoords()) {
            return [];
        }
        return self::where('id', '<>', $this->id)
                ->whereLatitude($this->latitude)
                ->whereLongitude($this->longitude)->get();
    }

    public function getSameSettlements1926Attribute() {
        if (!$this->hasCoords()) {
            return [];
        }
        return Settlement1926::whereLatitude($this->latitude)
                ->whereLongitude($this->longitude)->get();
    }

    public function getPossiblySameSettlements1926Attribute() {
        $settl_id = $this->id;
        $lat = $this->latitude;
        $long = $this->longitude;
        return Settlement1926::where(function ($q) use ($lat, $long) {
                    $q->whereNull('latitude')->orWhere('longitude')
                      ->orWhere('latitude', '<>', $lat)
                      ->orWhere('longitude', '<>', $long);
                })->whereIn('id', function ($q) use ($settl_id) {
                    $q->select('settlement1926_id')->from('toponyms')
                      ->whereIn('id', function ($q2) use ($settl_id) {
                          $q2->select('toponym_id')->from('settlement_toponym')
                             ->whereSettlementId($settl_id);
                      });
                })->get();
//dd(to_sql($s));        
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
    
    public function settlementString($lang_id='', $with_krl=true)
    {
        $info = [];
        
        if ($this->name) {
            $info[0] = $this->name;
            if ($with_krl) {
                if ($this->name_krl) {
                     $info[0] .=  ", ".$this->name_krl;
                }
                if ($this->name_vep) {
                     $info[0] .=  ", ".$this->name_vep;
                }                     
            }
        }
        
        if ($this->district) {
            $info[] = $this->district->name;
        }
        
        if ($this->region) {
            $info[] = $this->region->name;
        }
        
        return join(', ', $info);
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
        return $place ? $place->name : '';
    }
 
    public static function getNamesByIds($ids) {
        $out = [];
        $places = self::whereIn('id', $ids)->get();
        foreach ($places as $place) {
            $out[] = $place->name;
        }
        return implode(', ', $out);
    }
 
    public static function namesByIdsToString($ids) {
        $names = [];
        foreach ($ids as $id) {
            $names[] = self::getNameById($id);
        }
        return join(', ', $names);
    }
}

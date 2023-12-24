<?php

namespace App\Models\Dict;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Selsovet1926;

class Settlement1926 extends Model
{
//    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500; //Stop tracking revisions after 500 changes have been made.
    protected $revisionCreationsEnabled = true; // By default the creation of a new model is not stored as a revision. Only subsequent changes to a model is stored.
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settlements1926';
    public $timestamps = false;
    protected $fillable = ['selsovet_id','name_en','name_ru', 'name_krl', 'wd', 'latitude', 'longitude'];
    const SortList=['name_ru', 'id'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;    
    use \App\Traits\Methods\search\byNameKRL;
    use \App\Traits\Methods\sortList;
    use \App\Traits\Methods\wdURL;    

    //Scopes
    use \App\Traits\Scopes\WithCoords;
    
    use \App\Traits\Relations\HasMany\Toponyms;
    
    public static function boot()
    {
        parent::boot();
    }
    
    /**
     * Get the selsovet1926 which contains this settlement1926
     * One To Many (Inverse) / Belongs To
     * https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function selsovet1926()
    {
        //                                           'foreign_key', 'owner_key'
        return $this->belongsTo(Selsovet1926::class, 'selsovet_id', 'id');
    }
    
    public function selsovet1926Value()
    {
        return $this->selsovet_id ? [$this->selsovet_id] : [];
    }
    
    public function hasCoords()
    {
        return ($this->latitude && $this->longitude) ? true : false;
    }
    
    public function getSameSettlements1926Attribute() {
        if (!$this->hasCoords()) {
            return [];
        }
        return self::where('id', '<>', $this->id)
                ->whereLatitude($this->latitude)
                ->whereLongitude($this->longitude)->get();
    }

    public function getSameSettlementsAttribute() {
        if (!$this->hasCoords()) {
            return [];
        }
        return Settlement::whereLatitude($this->latitude)
                ->whereLongitude($this->longitude)->get();
    }

    public function getPossiblySameSettlementsAttribute() {
        $settl_id = $this->id;
        $lat = $this->latitude;
        $long = $this->longitude;

        return Settlement::where(function ($q) use ($lat, $long) {
                    $q->whereNull('latitude')->orWhere('longitude')
                      ->orWhere('latitude', '<>', $lat)
                      ->orWhere('longitude', '<>', $long);
                })->whereIn('id', function ($q) use ($settl_id) {
                    $q->select('settlement_id')->from('settlement_toponym')
                      ->whereIn('toponym_id', function ($q2) use ($settl_id) {
                          $q2->select('id')->from('toponyms')
                             ->where('settlement1926_id', $settl_id);
                      });
                })->get();
//dd(to_sql($s));        
    }
    /** Gets array of search parameters.
     * 
     * @param type $request
     * @return type
     */
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'in_desc'     => (int)$request->input('in_desc'),
                    'search_districts1926'   => (array)$request->input('search_districts1926'),
                    'search_name'    => $request->input('search_name'),
                    'search_regions'     => (array)$request->input('search_regions'),
                    'search_selsovets1926' => (array)$request->input('search_selsovets1926'),
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
        
        $settlements = self::searchByName($settlements, $url_args['search_name']);
        $settlements = self::searchByLocation($settlements, $url_args['search_regions'], $url_args['search_districts1926']);
        
        if ($url_args['search_selsovets1926']) {
            $settlements = $settlements->whereIn('selsovet_id',$url_args['search_selsovets1926']);
        }         
//dd(to_sql($settlements)  );      
        return $settlements;
    }
    
    public static function searchByLocation($settlements, $regions, $districts) {
        
        if(!sizeof($regions) && !sizeof($districts)) {
            return $settlements;
        }
        
        return $settlements->whereIn('selsovet_id', function($q) use ($regions, $districts) {
                        $q->select('id')->from('selsovets1926');
                        if (sizeof($districts)) {
                            $q->whereIn('district1926_id', $districts);
                        }
                        if (sizeof($regions)) {
                            $q->whereIn('district1926_id', function($query) use ($regions) {
                              $query -> select ('id') -> from ('districts1926') 
                                      -> whereIn('region_id', $regions );
                            });
                        }
                });
    }
}

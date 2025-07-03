<?php

namespace App\Models\Dict;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\District1926;

class Selsovet1926 extends Model
{
//    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = false; // Don't remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 999999; // Stop tracking revisions after 999999 changes have been made.
    protected $revisionCreationsEnabled = true; // By default the creation of a new model is not stored as a revision. Only subsequent changes to a model is stored.
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'selsovets1926';
    public $timestamps = false;
    protected $fillable = ['district1926_id','name_en','name_ru', 'name_krl'];
    const SortList=['name_ru', 'id'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\search\byNameKRL;
    use \App\Traits\Methods\sortList;
    
    public static function boot()
    {
        parent::boot();
    }
    
    public function getRegionIdAttribute()
    {
        return $this->district1926 ? $this->district1926->region_id : NULL;
    }
    
    public function regionValue()
    {
        return $this->region_id ? [$this->region_id] : [];
    }
    
    public function district1926Value()
    {
        return $this->district1926_id ? [$this->district1926_id] : [];
    }
    
    /**
     * Get the district1926 which contains this selsovet1926
     * One To Many (Inverse) / Belongs To
     * https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function district1926()
    {
        return $this->belongsTo(District1926::class);
    }
    
    public function settlements1926()
    {
        return $this->hasMany(Settlement1926::class, 'selsovet_id');
    }
    
    public function toponyms()
    {
        return $this->hasManyThrough(Toponym::class, Settlement1926::class, 'selsovet_id', 'settlement1926_id', 'id', 'id');
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
                    'sort_by' => $request->input('sort_by'),
                ];
        $sort_list = self::SortList;
        if (!in_array($url_args['sort_by'], $sort_list)) {
            $url_args['sort_by']= $sort_list[0];
        }
        return remove_empty_elems($url_args);
    }
    
    public static function search(Array $url_args) {
        
        $selsovets = self::orderBy($url_args['sort_by'], $url_args['in_desc'] ? 'DESC' : 'ASC');
        
        $selsovets = self::searchByName($selsovets, $url_args['search_name']);
        $selsovets = self::searchByRegion($selsovets, $url_args['search_regions']);
        
        if ($url_args['search_districts1926']) {
            $selsovets = $selsovets->whereIn('district1926_id',$url_args['search_districts1926']);
        }         
        return $selsovets;
    }
    
    public static function searchByRegion($selsovets, $search_regions) {
        
        if(!sizeof($search_regions)) {
            return $selsovets;
        }
        
        return $selsovets->whereIn('district1926_id', function($query) use ($search_regions) {
            $query -> select ('id') -> from ('districts1926') 
                    -> whereIn('region_id', $search_regions );
        });
    }
    
    public static function getList($short=false, $region_id=null)
    {     
        $locale = app()->getLocale();
        $field_name = $short ? 'short' : 'name';
        
        $objects = self::orderBy($field_name.'_'.$locale);
        if (!empty($region_id)) {
            $objects->whereIn('district1926_id', function ($q) use ($region_id) {
                $q->select('id')->from('districts1926')
                  ->whereRegionId($region_id);
            });
        }
        $objects = $objects->get();
        $list = array();
        foreach ($objects as $row) {
            $list[$row->id] = $row->{$field_name};
        }
        
        return $list;         
    }
}

<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\District1926;

class Selsovet1926 extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'selsovets1926';
    public $timestamps = false;
    protected $fillable = ['district1926_id','name_en','name_ru', 'name_krl'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    
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
                    'search_districts1926'   => (array)$request->input('search_districts1926'),
                    'search_name'    => $request->input('search_name'),
                    'search_regions'     => (array)$request->input('search_regions'),
                ];
        return $url_args;
    }
    
    public static function search(Array $url_args) {
        
        $selsovets = self::orderBy('name_ru');
        
        $selsovets = self::searchByName($selsovets, $url_args['search_name']);
        $selsovets = self::searchByRegion($selsovets, $url_args['search_regions']);
        
        if ($url_args['search_districts1926']) {
            $selsovets = $selsovets->whereIn('district1926_id',$url_args['search_districts1926']);
        }         
        return $selsovets;
    }
    
    public static function searchByName($selsovets, $search_name) {
        
        if(!$search_name) {
            return $selsovets;
        }
        
        $selsovets = $selsovets->where(function($query) use ($search_name) {
            $query -> where('name_ru','LIKE',$search_name)
                   -> whereOr('name_en','LIKE',$search_name)
                   -> whereOr('name_krl','LIKE',$search_name);
        });
        
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
}

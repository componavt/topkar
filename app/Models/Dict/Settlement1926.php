<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Selsovet1926;

class Settlement1926 extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settlements1926';
    public $timestamps = false;
    protected $fillable = ['selsovet_id','name_en','name_ru', 'name_krl'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;    
    use \App\Traits\Methods\search\byNameKRL;
    
    use \App\Traits\Relations\HasMany\Toponyms;
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
                    'search_selsovets1926' => (array)$request->input('search_selsovets1926'),
                ];
        return $url_args;
    }
    
    public static function search(Array $url_args) {
        
        $settlements = self::orderBy('name_ru');
        
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

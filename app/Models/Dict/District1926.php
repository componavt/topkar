<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Region;

class District1926 extends Model
{
    use HasFactory;
    protected $table = 'districts1926';
    public $timestamps = false;
    protected $fillable = ['region_id','name_en','name_ru'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\search\byName;
    
    use \App\Traits\Relations\BelongsTo\Region;
    use \App\Traits\Relations\HasMany\Selsovets1926;
    
    public function toponyms()
    {
        $district_id = $this->id;
        return Toponym::whereIn('settlement1926_id', function($q2) use ($district_id) {
                    $q2 -> select ('id') -> from ('settlements1926')
                        -> whereIn('selsovet_id', function($q3) use ($district_id) {
                            $q3 -> select ('id') -> from ('selsovets1926')
                                -> where('district1926_id', $district_id);
                        });
                });
    }
    
    /** Gets array of search parameters.
     * 
     * @param type $request
     * @return type
     */
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'search_regions'     => (array)$request->input('search_regions'),
                    'search_name'    => $request->input('search_name'),
                ];
        return $url_args;
    }
    
    /** Search district by various parameters. 
     * 
     * @param array $url_args
     * @return type
     */
    public static function search(Array $url_args) {
        
        $districts = self::orderBy('region_id')->orderBy('name_ru');
        
        $districts = self::searchByName($districts, $url_args['search_name']);
        
        if ($url_args['search_regions']) {
            $districts = $districts->whereIn('region_id',$url_args['search_regions']);
        }         
        return $districts;
    }    
}

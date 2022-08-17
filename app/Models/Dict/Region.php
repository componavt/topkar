<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\District1926;
//use LaravelLocalization;

class Region extends Model
{
    use HasFactory;
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    
    
    /**
     * Get the old districts for the region.
     */
    public function districts1926()
    {
        return $this->hasMany(District1926::class);
    }
    
    public function toponyms()
    {
        $region_id = $this->id;
        return Toponym::whereIn('district_id', function($query) use ($region_id) {
                    $query -> select ('id') -> from ('districts') 
                            -> where('region_id', $region_id);
                })->orWhere(function ($q1) use ($region_id) {
                    $q1-> whereIn('settlement1926_id', function($q2) use ($region_id) {
                        $q2 -> select ('id') -> from ('settlements1926')
                            -> whereIn('selsovet_id', function($q3) use ($region_id) {
                                $q3 -> select ('id') -> from ('selsovets1926')
                                    -> whereIn('district1926_id', function($q4) use ($region_id) {
                                    $q4 -> select ('id') -> from ('districts1926') 
                                        -> where('region_id', $region_id);
                                    });
                                });
                        });
                });
    }
    
}

<?php namespace App\Traits\Scopes;

/*
 * for Toponym, Settlement, Settlement1926
 */
trait WithCoords
{    
    public static function scopeWithCoords($builder) {
        return $builder->whereNotNull('latitude')
                       ->whereNotNull('longitude')
                       ->where('latitude', '>', 0)
                       ->where('longitude', '>', 0);
    }
}    


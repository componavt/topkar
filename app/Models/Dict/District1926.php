<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Region;

class District1926 extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'districts1926';
    
    
    /**
     * Get localized name (->name).
     * If name in English is absent, then return name in Russian
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $r = $this->{'name_'.$locale};
        
        if( empty($r) and $locale == "en") 
            $r = $this->{'name_ru'};
        
        return $r;
    }
    
    /**
     * Get the region which contains this district
     * One To Many (Inverse) / Belongs To
     * https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}

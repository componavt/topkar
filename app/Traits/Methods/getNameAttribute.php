<?php namespace App\Traits\Methods;

trait getNameAttribute
{
    /** Gets name of this object, takes into account locale.
     * 
     * @return String
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $r = $this->{'name_'.$locale};
        
        if( empty($r) and $locale == "en") 
            $r = $this->{'name_ru'};
        
        if( empty($r) and $locale == "ru") 
            $r = $this->{'name_en'};
        
        if( empty($r) )
            $r = $this->{'name_krl'};
        return $r;
    }
}
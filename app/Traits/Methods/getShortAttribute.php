<?php namespace App\Traits\Methods;

trait getShortAttribute
{
    /** Gets name of this object, takes into account locale.
     * 
     * @return String
     */
    public function getShortAttribute()
    {
        $locale = app()->getLocale();
        $r = $this->{'short_'.$locale};
        
        if( empty($r)) {
            $r = $this->{'name_'.$locale};
        }
        if( empty($r) and $locale == "en") {
            $r = $this->{'short_ru'};
        }
        if( empty($r) and $locale == "ru") {
            $r = $this->{'short_en'};
        }
        return $r;
    }
}
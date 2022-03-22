<?php namespace App\Traits\Methods;

trait getNameAttribute
{
    /** Gets name of this object, takes into account locale.
     * 
     * @return String
     */
    public function getNameAttribute() : String
    {
        $locale = app()->getLocale();
        $r = $this->{'name_'.$locale};
        
        if( empty($r) and $locale == "en") 
            $r = $this->{'name_ru'};
        
        return $r;
    }
}
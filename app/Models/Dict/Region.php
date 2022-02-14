<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\District1926;

class Region extends Model
{
    use HasFactory;
    
    
    /**
     * Get localized name (->name).
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'name_'.$locale};
    }
    
    
    /**
     * Get the old districts for the region.
     */
    public function districts1926()
    {
        return $this->hasMany(District1926::class);
    }
}

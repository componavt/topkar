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
}

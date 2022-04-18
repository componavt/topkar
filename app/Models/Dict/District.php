<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Region;

class District extends Model
{
    use HasFactory;
    use \App\Traits\Methods\getNameAttribute;
    
    /**
     * Get the district which contains this toponym
     * One To Many (Inverse) / Belongs To, https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}

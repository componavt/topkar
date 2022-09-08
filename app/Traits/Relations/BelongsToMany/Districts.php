<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Dict\District;

trait Districts
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function districts(){
        return $this->belongsToMany(District::class)
                    ->withPivot('include_from','include_to')
                    ->orderBy('name_ru');
    }    
}


<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Dict\Settlement1926;

trait Settlements1926
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function settlements1926(){
        return $this->belongsToMany(Settlement1926::class);
    }    
}


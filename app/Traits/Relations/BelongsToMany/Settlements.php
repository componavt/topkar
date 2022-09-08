<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Dict\Settlement;

trait Settlements
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function settlements(){
        return $this->belongsToMany(Settlement::class);
    }    
}


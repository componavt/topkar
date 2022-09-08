<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Misc\Informant;

trait Informants
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function informants(){
        return $this->belongsToMany(Informant::class);
    }    
}


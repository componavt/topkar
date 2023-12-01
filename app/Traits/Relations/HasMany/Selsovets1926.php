<?php namespace App\Traits\Relations\HasMany;

use App\Models\Dict\Selsovet1926;

trait Selsovets1926
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function selsovets1926(){
        return $this->hasMany(Selsovet1926::class);
    }    
}


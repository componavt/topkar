<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Dict\Toponym;

trait Toponyms
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function toponyms(){
        return $this->belongsToMany(Toponym::class);
    }    
}


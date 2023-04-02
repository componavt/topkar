<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Misc\Source;

trait Sources
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sources(){
        return $this->belongsToMany(Source::class);
    }    
}


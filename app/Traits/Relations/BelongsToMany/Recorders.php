<?php namespace App\Traits\Relations\BelongsToMany;

trait Recorders
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recorders(){
        return $this->belongsToMany(\App\Models\Misc\Recorder::class);
    }    
}


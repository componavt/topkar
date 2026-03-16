<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Misc\Struct;

trait Structs
{
    /**
     * The structures that belong to the toponym. (many to many relation)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function structs()
    {
        return $this->belongsToMany(Struct::class);
    }
}


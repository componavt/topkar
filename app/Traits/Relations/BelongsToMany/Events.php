<?php namespace App\Traits\Relations\BelongsToMany;

trait Events
{
   public function events()
    {
        return $this->belongsToMany(\App\Models\Misc\Event::class);
    }
}
<?php namespace App\Traits\Relations\HasMany;

trait Toponyms
{
   public function toponyms()
    {
        return $this->hasMany(\App\Models\Dict\Toponym::class);
    }
}
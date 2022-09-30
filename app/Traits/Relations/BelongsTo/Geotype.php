<?php namespace App\Traits\Relations\BelongsTo;

trait Geotype
{
    /**
     * Get the geotype which contains this toponym or settlement
     * One To Many (Inverse) / Belongs To
     */
    public function geotype()
    {
        //                                      'foreign_key','owner_key'
        return $this->belongsTo(\App\Models\Misc\Geotype::class, 'geotype_id', 'id');
    }
}
<?php namespace App\Traits\Relations\BelongsTo;

trait Source
{
    /**
     * Get the source which contains this toponym or settlement
     * One To Many (Inverse) / Belongs To
     */
    public function source()
    {
        return $this->belongsTo(\App\Models\Misc\Source::class);
    }
}
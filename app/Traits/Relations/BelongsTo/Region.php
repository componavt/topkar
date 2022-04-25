<?php namespace App\Traits\Relations\BelongsTo;

trait Region
{
    /**
     * Get the region which contains this district
     * One To Many (Inverse) / Belongs To
     * https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(\App\Models\Dict\Region::class);
    }
}
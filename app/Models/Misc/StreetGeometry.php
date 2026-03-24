<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\Street;

class StreetGeometry extends Model
{
    protected $fillable = [
        'street_id',
        'source_name',
        'geojson',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function street()
    {
        return $this->belongsTo(Street::class);
    }
}

<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\Street;

class StreetGeometry extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionable = ['updated_at'];
    protected $dontKeepRevisionOf = [];
    protected $revisionEnabled = true;
    protected $revisionCleanup = false;
    protected $historyLimit = 999999;
    protected $revisionCreationsEnabled = true;
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );

    public function getRevisionFormattedFieldNames()
    {
        return [
            'name' => 'source_name',
        ];
    }

    protected $fillable = [
        'street_id',
        'source_name',
        'geojson',
        'meta',
    ];

    protected $casts = [
        'geojson' => 'array',
        'meta' => 'array',
    ];

    public function street()
    {
        return $this->belongsTo(Street::class);
    }
}

<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Model;
use App\Models\Misc\Geotype;

class Street extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Traits\Relations\BelongsTo\Geotype;
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getNameById;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\sortList;
    use \App\Traits\Search\StreetSearch;

    protected $revisionEnabled = true;
    protected $revisionCleanup = false;
    protected $historyLimit = 999999;
    protected $revisionCreationsEnabled = true;
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );

    protected $fillable = ['name_ru', 'name_krl', 'name_fin', 'geotype_id', 'history'];

    protected $casts = [
        'geotype_id' => 'integer',
    ];

    const SortList = ['name_ru', 'id'];
}

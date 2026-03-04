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
    use \App\Traits\Modify\LogsUpdatedAt;
    use \App\Traits\Search\StreetSearch;

    protected $revisionable = ['updated_at'];
    protected $dontKeepRevisionOf = [];
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

    /**
     * Допустимые типы геообъектов для улиц (урбанонимы)
     * ID геотипов из таблицы geotypes
     */
    const Types = [
        62, // улица (street)
        131, // - проспект (avenue)
        132, // - площадь (square)
        133, // - проезд (passage)
        134, // - шоссе (highway)
        135, // - парк (park)
        136, // - бульвар (boulevard)
    ];
}

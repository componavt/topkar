<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Model;

use App\Models\Misc\StreetGeometry;
use App\Models\Misc\Structhier;

class Street extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;

    // Belongs To One Relations
    use \App\Traits\Relations\BelongsTo\Geotype;

    // Belongs To Many Relations
    use \App\Traits\Relations\BelongsToMany\Structs;

    // Methods
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getNameById;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\sortList;

    use \App\Traits\History\ToponymHistory;
    use \App\Traits\Modify\LogsRelationRevisions;
    use \App\Traits\Modify\LogsUpdatedAt;
    use \App\Traits\Modify\StreetModify;
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

    public function getRevisionFormattedFieldNames()
    {
        return [
            'name' => 'name_ru',
        ];
    }


    protected $fillable = ['name_ru', 'name_krl', 'name_fi', 'geotype_id', 'history', 'main_info'];

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

    const Structhiers = [7, 8];

    public function geometry()
    {
        return $this->hasOne(StreetGeometry::class);
    }

    public function structhierList()
    {
        $list = [];
        foreach (self::Structhiers as $struct_id) {
            $list[$struct_id] = Structhier::find($struct_id)->full_name;
        }
        return $list;
    }
}

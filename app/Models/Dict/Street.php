<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Model;

use App\Models\Misc\Geotype;
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

    const SortList = ['sort_name', 'id'];

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
        137, // - акватория (water area)
        138, // - аллея (alley)
        140, // - банка (bank)
        141, // - водохранилище (reservoir)
        142, // - гавань (harbor)
        143, // - затон (backwater)
        144, // - источник (source)
        147, // - канавка (runnel)
        148, // - канал (canal)
        149, // - квартал (quarter)
        150, // - ключ (spring)
        151, // - магистраль (main road)
        152, // - месторождение (deposit)
        153, // - микрорайон (microdistrict)
        154, // - набережная (embankment)
        155, // - округ (borough)
        156, // - плёсы (reaches)
        157, // - переулок (lane)
        158, // - пороги (rapids)
        159, // - порт (port)
        160, // - проулок (passageway)
        161, // - район (district)
        162, // - сад (garden)
        164, // - сквер (small park)
        165, // - спуск (descent)
        167, // - тракт (tract)
        168, // - тупик (dead end)
        169, // - часть (part)
        93, // - город (city)
        33, // - губа (bay)
        46, // - залив (gulf)
        25, // - озеро (lake)
        5, // - омут (deep pool)
        51, // - озеро (lake)
        36, // - пруд (pond)
        103, // - разъезд (junction)
        26,  // - река (river)
        8, // - родник (springhead)
        23, // - ручей (stream)
        69, // - территория (territory)
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

    // Статичный — принимает строку, используется в сидере и любом другом месте
    // Street::parseNameForSort('улица Ленина') => [geotype_id, 'ленина']
    public static function parseNameForSort(string $nameRu): array
    {
        $nameForSearch = trim(preg_replace('/\b\d\S*\s*/u', '', $nameRu, 1));
        $types = Geotype::streetTypes();

        [$geotypeId, $nameForSearch] = cut_word_at_start($nameForSearch, $types)
            ?? cut_word_at_end($nameForSearch, $types)
            ?? [null, $nameForSearch];

        return [$geotypeId, $nameForSearch];
    }

    // Нестатичный — берёт name_ru из самой модели и сохраняет поля
    // $street->syncSortName()
    public function syncSortName(): void
    {
        [$geotype_id, $this->sort_name] = static::parseNameForSort($this->name_ru);
    }

    // Автоматически при каждом сохранении
    protected static function booted(): void
    {
        static::saving(function (Street $street) {
            [$geotype_id, $street->sort_name] = static::parseNameForSort($street->name_ru);
        });
    }
}

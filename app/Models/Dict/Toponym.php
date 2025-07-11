<?php

namespace App\Models\Dict;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\Settlement1926;
//use App\Models\Dict\Source;
use App\Models\Dict\Wrongname;

use App\Models\Misc\Event;
use App\Models\Misc\EtymologyNation;
use App\Models\Misc\EthnosTerritory;
use App\Models\Misc\SourceToponym;
use App\Models\Misc\Struct;

class Toponym extends Model
{
//    use HasFactory;
    use \App\Traits\History\ToponymHistory;
    use \App\Traits\Modify\ToponymModify;
    use \App\Traits\Search\ToponymSearch;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionable = ['updated_at'];
    protected $dontKeepRevisionOf = [];
    protected $revisionEnabled = true;
    protected $revisionCleanup = false; // Don't remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 999999; // Stop tracking revisions after 999999 changes have been made.
    protected $revisionCreationsEnabled = true; // By default the creation of a new model is not stored as a revision. Only subsequent changes to a model is stored.
    protected $revisionFormattedFields = array(
//        'title'  => 'string:<strong>%s</strong>',
//        'public' => 'boolean:No|Yes',
//        'modified_at' => 'datetime:d/m/Y g:i A',
//        'deleted_at' => 'isEmpty:Active|Deleted'
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    protected $revisionFormattedFieldNames = array(
//        'title' => 'Title',
//        'small_name' => 'Nickname',
//        'deleted_at' => 'Deleted At'
    );

    public function getRevisionFormattedFieldNames() {
        return [
            'name' => 'toponym_name',
        ];
    }
    
//    protected $appends = ['settlement_id'];
//    protected $hidden = ['settlement_id']; // чтобы не мешал в toArray()
    protected $fillable = ['name', 'name_for_search', 'district_id', 'lang_id', 
                           'settlement1926_id', 'geotype_id', 'etymology', 
                           'etymology_nation_id', 'ethnos_territory_id', 
                           'caseform', 'main_info', 'folk', 'legend', 'wd', 
                           'latitude', 'longitude'];
    const SortList=['name', 'id'];
    const nLadogaDistricts = [6, 14, 9, 22];
    const nLadogaRegion1926 = 6;
    
    //use \App\Traits\Methods\getNameAttribute;    
    use \App\Traits\Methods\sortList;
    use \App\Traits\Methods\wdURL;    
    use \App\Traits\Modify\LogsRelationRevisions;
    use \App\Traits\Modify\LogsUpdatedAt;
    
    //Scopes
    use \App\Traits\Scopes\WithCoords;
    
    // Belongs To One Relations
    use \App\Traits\Relations\BelongsTo\Lang;
    use \App\Traits\Relations\BelongsTo\Geotype;
    
    // Belongs To Many Relations
    use \App\Traits\Relations\BelongsToMany\Settlements;
    use \App\Traits\Relations\BelongsToMany\Sources;
    use \App\Traits\Relations\BelongsToMany\Texts;
    
    public static function boot()
    {
        parent::boot();
        static::updating(function ($model) {
            \Log::info('Dirty fields:', $model->getDirty());
        });
        
    }
    
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function settlementsToString($with_links=false) {
        $out = [];
        foreach ($this->settlements as $settlement) {
            $name = $settlement->name;
            if ($with_links) {
                $name = '<a href="'.route('settlements.show', $settlement->id).'">'.$name.'</a>';
            }
            $out[] = $name;
        }
        return join(', ', $out);
    }    
    
    /**
     * Get the settlement1926 which contains this toponym
     * One To Many (Inverse) / Belongs To.
     * One settlement1926 and many toponyms.
     * https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function settlement1926()
    {
        return $this->belongsTo(Settlement1926::class);
    }
    
    /**
     * Get the district which contains this toponym
     * One To Many (Inverse) / Belongs To, https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function district()
    {
        //                                       'foreign_key', 'owner_key'
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
    
    
    /**
     * Get the etymology nation name which contains this toponym
     * One To Many (Inverse) / Belongs To
     */
    public function etymologyNation()
    {
        return $this->belongsTo(EtymologyNation::class);
    }
    
    /**
     * Get the geotype which contains this toponym
     * One To Many (Inverse) / Belongs To
     */
    public function ethnosTerritory()
    {
        return $this->belongsTo(EthnosTerritory::class);
    }
    
    public function informants()
    {
        return $this->hasManyThrough('Informant', 'Event', 'toponym_id', 'event_id');
    }
    
    /**
     * The structures that belong to the toponym. (many to many relation)
     */
    public function structs()
    {
        return $this->belongsToMany(Struct::class);
    }
    
    public function topnames()
    {
        return $this->hasMany(Topname::class);
    }
    
    public function wrongnames()
    {
        //                                       
        return $this->hasMany(Wrongname::class);
    }
    
    public function topnamesWithLangs(){
        $out = [];
        $settlements = $this->settlements;
        $url = '&search_geotypes[]='.$this->geotype_id.
                     '&search_settlements1926_id[]='.$this->settlement1926_id;
        foreach($settlements as $s) {
            $url .= '&search_settlements[]='.$s->id;
        }
        foreach ($this->topnames as $topname) {
            $t = $topname->name;
            $toponyms = self::where('name','like',$this->name)
                            ->where('id','<>',$this->id)
                            ->whereGeotypeId($this->geotype_id);
            if ($this->settlement1926_id) {
                $toponyms->whereSettlement1926Id($this->settlement1926_id);
            }
            if (sizeof($settlements)) {
                $toponyms->whereIn('id', function ($q) use ($settlements) {
                                $q->select('toponym_id')->from('settlement_toponym')
                                ->whereIn('id', $settlements);
                         });
            }
            if ($toponyms->count()) {
                $t = '<a href="'.route('toponyms.index').'?search_toponym='.
                     $topname->name.$url.'">'.$t.'</a>';
            }
            if ($topname->lang) {
                $t .= ' ('.$topname->lang->short.')';
            }
            $out[] = $t;
        }
        return $out;
    }

    public function wrongnamesWithLangs(){
        $out = [];
        foreach ($this->wrongnames as $name) {
            $out[] = $name->name. ($name->lang ? ' ('.$name->lang->short.')' : '');
        }
        return $out;
    }

    public function sourceToponyms()
    {
        //                                       
        return $this->hasMany(SourceToponym::class)
                ->orderBy('sequence_number');
    }
        
    /**
     * Get 'Region, district, SETTLEMENT (String)' concatenated by comma.
     */
    public function getLocationAttribute()
    {
        if (!$this->region_name && !$this->district_name && !$this->settlementsToString()) {
            return '-';
        }
        return $this->region_name.', '. 
               $this->district_name.', '. 
               $this->settlementsToString();
    }
    
    public function getLocationWithLinkAttribute()
    {
        if (!$this->region_name && !$this->district_name && !$this->settlementsToString()) {
            return '-';
        }
        return $this->region_name.', '. 
               $this->district_name.', '. 
               $this->settlementsToString(true);
    }
    
    public function getRegionNameAttribute()
    {

        return optional(optional($this->district)->region)->name; 
    }
    
    public function getDistrictNameAttribute()
    {
        if( $this->district ) 
        { 
            return $this->district->name; 
        }
        return "";
    }
    
    public function getSettlementNameAttribute()
    {
        return $this->settlementsToString();
    }
    
    /**
     * Get 'Region, district1926, selsovet1926, settlement1926' concatenated by comma.
     */
    public function getLocation1926Attribute()
    {
        if (!$this->region1926_name && !$this->district1926_name
               && !$this->selsovet1926_name && !$this->settlement1926_name) {
            return '-';
        }
        return $this->region1926_name.', '. 
               $this->district1926_name.', '. 
               $this->selsovet1926_name.', '.
               $this->settlement1926_name;
    }

    public function getLocation1926WithLinkAttribute()
    {
        if (!$this->region1926_name && !$this->district1926_name
               && !$this->selsovet1926_name && !$this->settlement1926_name) {
            return '-';
        }
        return $this->region1926_name.', '. 
               $this->district1926_name.', '. 
               $this->selsovet1926_name.', '.
               '<a href="'.route('settlements1926.show', $this->settlement1926_id).'">'.$this->settlement1926_name.'</a>';
    }
    
    public function getSelsovet1926IdAttribute()
    {
        return $this->settlement1926 
                    ? $this->settlement1926->selsovet_id : NULL;
    }

    public function getDistrict1926IdAttribute()
    {
        return $this->settlement1926 && $this->settlement1926->selsovet1926
                    ? $this->settlement1926->selsovet1926->district1926_id : NULL;
    }

    public function getRegion1926IdAttribute()
    {
        return $this->settlement1926 && $this->settlement1926->selsovet1926  
                        && $this->settlement1926->selsovet1926->district1926
                    ? $this->settlement1926->selsovet1926->district1926->region_id : NULL;
    }
    
    public function getRegionIdAttribute()
    {
        return $this->district ? $this->district->region_id : NULL;
    }
    /**
     * Get name of region via selsovet1926->district1926.
     * If name is absent, then return empty string.
     */
    public function getRegion1926NameAttribute()
    {
        if( $this->settlement1926 &&
            $this->settlement1926->selsovet1926 &&
            $this->settlement1926->selsovet1926->district1926 &&
            $this->settlement1926->selsovet1926->district1926->region ) 
        { 
            return $this->settlement1926->selsovet1926->district1926->region->name; 
        }
        
        return "";
    }
    
    public function getDistrict1926NameAttribute()
    {
        if( $this->settlement1926 &&
            $this->settlement1926->selsovet1926 &&
            $this->settlement1926->selsovet1926->district1926 ) 
        { 
            return $this->settlement1926->selsovet1926->district1926->name; 
        }
        
        return "";
    }
    
    // selsovet1926->name
    public function getSelsovet1926NameAttribute()
    {
        if( $this->settlement1926 &&
            $this->settlement1926->selsovet1926 ) 
        { 
            return $this->settlement1926->selsovet1926->name; 
        }
        
        return "";
    }
    
    public function getSettlement1926NameAttribute()
    {
        return $this->settlement1926 ? $this->settlement1926->name : '';
    }
    
    public function geotypeValue()
    {
        return $this->geotype_id ? [$this->geotype_id] : [];
    }
    
    public function ethnosTerritoryValue()
    {
        return $this->ethnos_territory_id ? [$this->ethnos_territory_id] : [];
    }
    
    public function etymologyNationValue()
    {
        return $this->etymology_nation_id ? [$this->etymology_nation_id] : [];
    }
    
    public function regionValue()
    {
        return $this->region_id ? [$this->region_id] : [];
    }
    
    public function region1926Value()
    {
        return $this->region1926_id ? [$this->region1926_id] : [];
    }
    
    public function districtValue()
    {
        return $this->district_id ? [$this->district_id] : [];
    }
    
    public function district1926Value()
    {
        return $this->district1926_id ? [$this->district1926_id] : [];
    }
    
    public function selsovet1926Value()
    {
        return $this->selsovet1926_id ? [$this->selsovet1926_id] : [];
    }
    
    public function settlementValue()
    {
        return $this->settlements ? $this->settlements()->pluck('id')->toArray() : '';
    }
    
    public function settlement1926Value()
    {
        return $this->settlement1926_id ? [$this->settlement1926_id] : '';
    }
    
    public function hasCoords()
    {
        return ($this->latitude>0 && $this->longitude>0) ? true : false;
    }
    
    public function objOnMap()
    {
        if ($this->hasCoords()) {
            return $this;
        }
        if ($this->settlement1926 && $this->settlement1926->hasCoords()) {
            return $this->settlement1926;
        }
        if (!empty($this->settlements[0]) && $this->settlements[0]->hasCoords()) {
            return $this->settlements[0];
        }
        return null;
    }
    
    public function argsForAnotherOne($args_by_get='') {
        $args = [];
        foreach ($this->settlements as $settlement) {
            $args[] = 'settlement_id[]='.$settlement->id;
        }
        if ($this->district_id) {
            $args[] = 'district_id='.$this->district_id;
            if ($this->district->region_id) {
                $args[] = 'region_id='.$this->district->region_id;
            }
        }
        if ($this->settlement1926_id) {
            $args[] = 'settlement1926_id='.$this->settlement1926_id;
            if ($this->settlement1926->selsovet_id) {
                $args[] = 'selsovet1926_id='.$this->settlement1926->selsovet_id;
            }
            if ($this->settlement1926->selsovet1926->district1926_id) {
                $args[] = 'district1926_id='.$this->settlement1926->selsovet1926->district1926_id;
            }
            if ($this->settlement1926->selsovet1926->district1926->region_id) {
                $args[] = 'region1926_id='.$this->settlement1926->selsovet1926->district1926->region_id;
            }
        }
        foreach ($this->events as $event) {
            $args[] = 'event_id[]='.$event->id;
        }
        if ($this->ethnos_territory_id) {
            $args[] = 'ethnos_territory_id='.$this->ethnos_territory_id;
        }
        if (!sizeof($args)) {
            return $args_by_get;
        }
        $args = join('&',$args);
        return $args_by_get ? $args_by_get.'&'.$args : '?'.$args;
    }

    public function anothersInSettlement($geotype_id=null) {
        $settlements = $this->settlements()->pluck('id')->toArray();
        $settlement1926_id = $this->settlement1926_id;
//dd($settlement1926_id);        
        if (!sizeof($settlements) && !$settlement1926_id) {
            return [];
        }
        $toponyms = self::where('id', '<>', $this->id)->orderBy('name');
        if (sizeof($settlements)) {
            $toponyms->whereIn('id', function ($q) use ($settlements) {
                $q->select('toponym_id')->from('settlement_toponym')
                  ->whereIn('settlement_id', $settlements);
            });
        }
//dd($settlements, $settlement1926_id, to_sql($toponyms), $toponyms->get());        
//dd($settlement1926_id);        
        
        if ($settlement1926_id) {
//dd($settlement1926_id);        
            $toponyms->where('settlement1926_id', $settlement1926_id);
//            $toponyms->where('settlement1926_id', 2427);
//            $toponyms = $toponyms->where('settlement1926_id', (int)$settlement1926_id);
//            $toponyms = $toponyms->whereSettlement1926Id($settlement1926_id);
//            $toponyms->whereIn('settlement1926_id', [$settlement1926_id]);
        }
//dd($toponyms->get());        
//dd(to_sql($toponyms));    
        $toponyms_with = collect();
        $toponyms_without = collect();
        
        foreach ($toponyms->get() as $t) {
            $t->geotype_name = $t->geotype ? ($t->geotype->short ? $t->geotype->short : $t->geotype->name) : '';
            if ($t->geotype_id == $geotype_id) {
                $toponyms_with->push($t);
            } else {
                $toponyms_without->push($t);
            }
        }
        $toponyms_without=$toponyms_without->sortBy(['geotype_name','name']);
//dd($toponyms_without);        
        return $toponyms_with->merge($toponyms_without);
    }

    public function fromNLadoga() {
        if (in_array($this->district_id, self::nLadogaDistricts)) {
            return true;
        }
        return false;
    }
        
}

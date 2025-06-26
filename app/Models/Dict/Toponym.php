<?php

namespace App\Models\Dict;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Venturecraft\Revisionable\Revision;

use App\Models\User;

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
    use \App\Traits\ToponymSearch;
    use \App\Traits\Methods\LogsUpdatedAt;
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
    
    protected $fillable = ['name', 'name_for_search', 'district_id', 'lang_id', 
                           'settlement1926_id', 'geotype_id', 'etymology', 
                           'etymology_nation_id', 'ethnos_territory_id', 
                           'caseform', 'main_info', 'folk', 'legend', 'wd', 
                           'latitude', 'longitude'];
    const SortList=['name', 'id'];
    const nLadogaDistricts=[6, 14, 9, 22];
    
    //use \App\Traits\Methods\getNameAttribute;    
    use \App\Traits\Methods\wdURL;    
    use \App\Traits\Methods\sortList;
    
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
        //                                       
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

    public static function storeData(array $data, $request) {
        $toponym = Toponym::create($data); 
        $toponym->name_for_search = to_search_form($toponym->name);
        $toponym->save(); 
        
        $toponym->updateAddInfo($data, $request);
        
        return $toponym;
    }
    
    public function updateData(array $data, $request) {
        $this->fill($data);
        if (!$this->district_id && $this->settlement && $this->settlement->district_id) {
            $this->district_id = $this->settlement->district_id;
        }
        $this->name_for_search = to_search_form($this->name);
        $this->save();
        
        foreach ((array)$request->topnames as $t_id => $t_info) {
            $topname = Topname::find($t_id);
            if (!$t_info['n']) {
                $topname->delete();
            } else {
                $topname->updateData($t_info); 
            }
        }
        
        foreach ((array)$request->wrongnames as $t_id => $t_info) {
            $wrongname = Wrongname::find($t_id);
            if (!$t_info['n']) {
                $wrongname->delete();
            } else {
                $wrongname->updateData($t_info); 
            }
        }
        
        foreach ((array)$request->source_toponym as $st_id => $st_data) {
            SourceToponym::find($st_id)->updateData($st_data);
        }
        foreach ((array)$request->events as $e_id => $e_data) {

            $event = Event::find($e_id);
            $event -> updateData($e_data);
        }
        
        $this->updateAddInfo($data, $request);        
    }
    
    public function updateAddInfo(array $data, $request) {
        $this->settlements()->sync(!empty($request->settlement_id) ? remove_empty($request->settlement_id) : []);
        
        foreach ((array)$request->new_topname as $t_info) {
            Topname::storeData($this->id, $t_info);
        }
        
        foreach ((array)$request->new_wrongname as $t_info) {
            Wrongname::storeData($this->id, $t_info);
        }

        foreach ((array)$request->new_source_toponym as $i => $st_data) {
            SourceToponym::storeData($this->id, $st_data);
        }
        
        foreach ((array)$request->new_events as $new_event) {
            Event::storeData($this->id, $new_event);
        }

        $structs = array_filter((array)$request->structs, 'strlen');        
        $this->structs()->sync($structs);   
        
        if ($data['text_ids']) {
            $this->texts()->sync(preg_split('/;\s*/', $data['text_ids']));
        } else {
            $this->texts()->detach();
        }   
        $this->logTouch();
    }
    
    public function remove() {
        $this->structs()->detach();
        $this->settlements()->detach();
        foreach ($this->sourceToponyms as $st) {
            $st->delete();
        } 
        foreach ($this->events as $event) {
            $event->remove();
        } 
        $this->delete();
    }
    
    public function fromNLadoga() {
        if (in_array($this->district_id, self::nLadogaDistricts)) {
            return true;
        }
        return false;
    }
    
    public static function lastCreated($limit='') {
        $toponyms = self::latest();
        if ($limit) {
            $toponyms = $toponyms->take($limit);
        }
        $toponyms = $toponyms->with('geotype')->get();
        
        $toponymIds = $toponyms->pluck('id')->all();

        // Получаем последние ревизии по созданию для всех топонимов
        $revisions = Revision::where('revisionable_type', 'like', '%Toponym')
            ->where('key', 'created_at')
            ->whereIn('revisionable_id', $toponymIds)
            ->latest()
            ->get()
            ->unique('revisionable_id')
            ->keyBy('revisionable_id');

        // Получаем id пользователей, чтобы не дёргать по одному
        $userIds = $revisions->pluck('user_id')->unique()->all();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // Назначаем user-имя каждому топониму
        foreach ($toponyms as $toponym) {
            $revision = $revisions->get($toponym->id);
            if ($revision) {
                $toponym->user = $users[$revision->user_id]->name ?? null;
            }
        }
        
        return $toponyms;
    }
    
    public static function lastUpdated($limit='',$is_grouped=0) {
        // Получаем ревизии одним запросом
        $revisions = Revision::where('revisionable_type', 'like', '%Toponym')
            ->where('key', 'updated_at')
            ->latest()
            ->get()
            ->unique('revisionable_id')  // берём только одну ревизию на каждый топоним
            ->take($limit);

        // Собираем id топонимов и пользователей
        $toponymIds = $revisions->pluck('revisionable_id')->all();
//dd($toponymIds);        
        $userIds = $revisions->pluck('user_id')->filter()->unique()->all();

        // Загружаем топонимы и пользователей пачкой
        $toponyms = self::whereIn('id', $toponymIds)->get()->keyBy('id');
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        $result = [];

        foreach ($revisions as $revision) {
            $toponym = $toponyms->get($revision->revisionable_id);
            if (!$toponym) {
                continue;
            }

            // Добавляем имя пользователя
            $toponym->user = $users[$revision->user_id]->name ?? null;

            if ($is_grouped) {
                $updated_date = $toponym->updated_at->formatLocalized(trans('general.date_format'));
                $result[$updated_date][] = $toponym;
            } else {
                $result[] = $toponym;
            }
        }

        return $result;
    }    

}

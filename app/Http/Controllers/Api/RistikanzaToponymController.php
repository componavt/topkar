<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Log;
use Response;

use App\Models\Dict\District;
use App\Models\Dict\District1926;
use App\Models\Dict\Selsovet1926;
use App\Models\Dict\Settlement;
use App\Models\Dict\Settlement1926;
use App\Models\Dict\Toponym;

use App\Models\Misc\Source;

class RistikanzaToponymController extends Controller
{
    protected function nLadogaUrlArgs(Request $request): array
    {
        $url_args = Toponym::urlArgs($request);

        /*
         * Вид объекта не выбирается в Ristikanza:
         * всегда только поселение.
         */
        $url_args['search_geotypes'] = [63];

        /*
         * Пользователь может сузить выборку до районов Северного Приладожья,
         * но не может запросить район за его пределами.
         */
        $allowedDistricts = Toponym::nLadogaDistricts;
        $requestedDistricts = $url_args['search_districts'] ?? [];

        if (!empty($requestedDistricts)) {
            $url_args['search_districts'] = array_values(
                array_intersect($requestedDistricts, $allowedDistricts)
            );
        } else {
            $url_args['search_districts'] = $allowedDistricts;
        }

        return $url_args;
    }

    protected function baseQuery(array $url_args)
    {
        return Toponym::search($url_args)
            ->whereIn('district_id', Toponym::nLadogaDistricts)
            ->where('geotype_id', 63);
    }

    public function index(Request $request)
    {
        $url_args = $this->nLadogaUrlArgs($request);

        $toponyms = Toponym::search($url_args)
            ->with([
                'topnames',
                'lang',
                'geotype',
            ]);
        $n_records = $toponyms->count();

        $toponyms = $toponyms->paginate(
            $url_args['portion']
        );
/*Log::debug('Ristikanza API locale', [
    'app_locale' => app()->getLocale(),
    'accept_language' => $request->header('Accept-Language'),
    'location' => $toponyms->first()->location ?? null,
]);*/        
        $items = $toponyms->getCollection()
            ->map(function ($toponym) {
                $geotype = $toponym->geotype
                    ? $toponym->geotype->name
                    : null;

                $topname = $toponym->topnames
                    ->map(function ($topname) {
                        return $topname->name;
                    })
                    ->filter()
                    ->implode(', ');

                return [
                    'id' => $toponym->id,
                    'name' => $toponym->name,
                    'topname' => $topname,
                    'geotype' => $geotype,
                    'location' => $toponym->location,
                    'location1926' => $toponym->location1926,
                    'latitude' => $toponym->latitude,
                    'longitude' => $toponym->longitude,
                ];
            })
            ->values()
            ->all();
    
        return response()->json([
            'data' => $items,
            'current_page' => $toponyms->currentPage(),
            'last_page' => $toponyms->lastPage(),
            'per_page' => $toponyms->perPage(),
            'total' => $toponyms->total(),

            /*
             * Временно можно оставить для проверки.
             * Потом это поле можно удалить.
             */
            'n_records' => $n_records,
        ]);
    }

    public function map(Request $request)
    {
        $url_args = $this->nLadogaUrlArgs($request);

        $url_args['search_geotypes'] = [63];
        $url_args['search_districts'] = Toponym::nLadogaDistricts;
        $url_args['map_height'] = $url_args['map_height'] ?? 1000;

        $limit = 3000;

        list($total_rec, $show_count, $objs, $limit, $bounds, $url_args)
            = Toponym::forMap($limit, $url_args);

        return response()->json([
            'data' => collect($objs)->map(function ($obj) {
                return [
                    'id' => $obj['id'],
                    'lat' => (float) $obj['lat'],
                    'lon' => (float) $obj['lon'],
                    'color' => $obj['color'],
                    'name' => $obj['name'],
                    'popup' => [
                        'title' => $obj['name'],
                        'topnames' => $obj['topnames'] ?? [],
                        'geotype' => $obj['geotype'] ?? null,
                        'location' => $obj['location'] ?? null,
                    ],
                ];
            })->values(),
            'meta' => [
                'total_rec' => $total_rec,
                'show_count' => $show_count,
                'limit' => $limit,
                'bounds' => $bounds,
            ],
        ]);
    }
    
    public function show($oikonym)
    {
        $toponym = Toponym::query()
            ->where('id', $id)
            ->whereIn('district_id', Toponym::nLadogaDistricts)
            ->where('geotype_id', 63)
            ->with([
                'lang',
                'geotype',
                'ethnosTerritory',
                'etymologyNation',

                'topnames.lang',
                'wrongnames.lang',

                'settlement1926',
                'settlements',

                'texts',

                'sourceToponyms.source',

                'structs.structhier.parent',

                'events.settlements',
                'events.settlements1926',
                'events.informants',
                'events.recorders',
            ])
            ->firstOrFail();

        return response()->json([
            'data' => $this->toponymData($toponym),
        ]);
    }   
    
    protected function toponymData(Toponym $toponym): array
    {
        $mapObject = $toponym->objOnMap();

        $map = null;

        if ($mapObject && $mapObject->hasCoords()) {
            $isOwnCoordinates = $mapObject instanceof Toponym
                && $mapObject->id == $toponym->id;

            $map = [
                'latitude' => (float) $mapObject->latitude,
                'longitude' => (float) $mapObject->longitude,
                'label' => $mapObject->name,
                'marker_color' => $isOwnCoordinates ? 'blue' : 'grey',
                'coordinate_source' => $isOwnCoordinates
                    ? 'toponym'
                    : ($mapObject === $toponym->settlement1926
                        ? 'settlement1926'
                        : 'settlement'),
            ];
        }

        return [
            'id' => $toponym->id,
            'name' => $toponym->name,

            'lang' => $toponym->lang ? [
                'short' => $toponym->lang->short,
            ] : null,

            'wikidata_url' => $toponym->wdURL(),

            'geotype' => $toponym->geotype ? [
                'id' => $toponym->geotype->id,
                'name' => $toponym->geotype->name,
                'short' => $toponym->geotype->short,
            ] : null,

            'topnames' => $toponym->topnames->map(function ($topname) {
                return [
                    'name' => $topname->name,
                    'lang' => $topname->lang ? $topname->lang->short : null,
                ];
            })->values(),

            'wrongnames' => $toponym->wrongnames->map(function ($wrongname) {
                return [
                    'name' => $wrongname->name,
                    'lang' => $wrongname->lang ? $wrongname->lang->short : null,
                ];
            })->values(),

            'location' => $toponym->location,
            'location_1926' => $toponym->location1926,

            'ethnos_territory' => $toponym->ethnosTerritory ? [
                'id' => $toponym->ethnosTerritory->id,
                'name' => $toponym->ethnosTerritory->name,
            ] : null,

            'main_info' => $toponym->main_info,

            'etymology_nation' => $toponym->etymologyNation ? [
                'id' => $toponym->etymologyNation->id,
                'name' => $toponym->etymologyNation->name,
            ] : null,

            'caseform' => $toponym->caseform,
            'etymology' => $toponym->etymology,
            'legend' => $toponym->legend,

            'texts' => $toponym->texts->map(function ($text) {
                return [
                    'id' => $text->id,
                    'title' => $text->title,
                    'url' => rtrim(env('VEPKAR_URL'), '/') .
                        '/' . app()->getLocale() .
                        '/corpus/text/' . $text->id,
                ];
            })->values(),

            'sources' => $toponym->sourceToponyms->map(function ($sourceToponym) {
                return [
                    'mention' => $sourceToponym->mention,
                    'source_name' => $sourceToponym->source
                        ? $sourceToponym->source->name
                        : null,
                    'source_text' => $sourceToponym->source_text,
                ];
            })->values(),

            'structs' => $toponym->structs->map(function ($struct) {
                return [
                    'name' => $struct->name,
                    'hierarchy' => $struct->structhier
                        ? trim(
                            optional($struct->structhier->parent)->name . ' ' .
                            $struct->structhier->name
                        )
                        : null,
                ];
            })->values(),

            'events' => $toponym->events->map(function ($event) {
                return [
                    'settlements' => $event->settlements->pluck('name')->values(),
                    'settlements_1926' => $event->settlements1926
                        ->pluck('name')
                        ->values(),
                    'date' => $event->date,
                    'informants' => $event->informants->map(function ($informant) {
                        return $informant->informantString();
                    })->values(),
                    'recorders' => $event->recorders
                        ->pluck('name_' . app()->getLocale())
                        ->filter()
                        ->values(),
                ];
            })->values(),

            'map' => $map,
        ];
    }
    
    public function oikonymFormValues()
    {
        $nladoga_districts = Toponym::nLadogaDistricts;
        $nladoga_region1926 = Toponym::nLadogaRegion1926;
        
        return response()->json([
            'district_values' => array_intersect_key(District::getList(), array_flip($nladoga_districts)),
            'district1926_values' => District1926::getList(false, $nladoga_region1926),
            'selsovet1926_values' => Selsovet1926::getList(false, $nladoga_region1926),
            'settlement_values' => Settlement::getList(),
            'settlement1926_values' => Settlement1926::getList(),
            'sort_values' => Toponym::sortList(),
            'source_values' => Source::getList(true),
        ]);
    }
    
    public function oikonymSources(Request $request)
    {
        $locale = app()->getLocale();
        
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'year_from' => ['nullable', 'integer', 'min:1', 'max:2100'],
            'year_to' => ['nullable', 'integer', 'min:1', 'max:2100'],
        ]);
        
        $sname = '%'.trim((string)($params['q'] ?? '')).'%';
        $year_from = $params['year_from'] ?? null;
        $year_to = $params['year_to'] ?? null;

        if ($year_from && $year_to && $year_from > $year_to) {
            return response()->json([]);
        }
        
        $sources = Source::query()
            ->when($sname !== '', function ($query) use ($sname) {
                $query->where('name_en',  'like',  $sname)
                      ->orWhere('name_ru','like',  $sname)
                      ->orWhere('short_en','like',  $sname)
                      ->orWhere('short_ru','like',  $sname);
            })
            ->when($year_from, function ($query) use ($year_from) {
                $query->where('year', '>=', $year_from);
            })
            ->when($year_to, function ($query) use ($year_to) {
                $query->where('year', '<=', $year_to);
            })
            ->orderBy('name_'.$locale)
            ->limit(50)
            ->get()
            ->map(function ($source) {
                return [
                    'id' => $source->id,
                    'text' => $source->short,
                ];
            })
            ->values()
            ->all();

        return response()->json($sources);
    }
    
    public function oikonymSettlements(Request $request)
    {
        $locale = app()->getLocale();
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'search_districts' => ['nullable', 'array'],
            'search_districts.*' => ['integer', 'min:1'],
        ]);
        
        $settlement_name = '%'.trim((string)($params['q'] ?? '')).'%';
        $districts = array_remove_null($params['districts'] ?? []);
        if (empty($districts)) {
            $districts = Toponym::nLadogaDistricts;
        }

        $settlements = Settlement::query()
            ->when($settlement_name !== '', function ($q) use ($settlement_name) {
                $q->where('name_en', 'like', $settlement_name)
                  ->orWhere('name_ru', 'like', $settlement_name);
            })
            ->when(sizeof($districts), function ($query) use ($districts) {
                $query->whereIn('id', function ($q) use ($districts) {
                    $q->select('settlement_id')->from('district_settlement')
                      ->whereIn('district_id', $districts);
                });                
            })
            ->orderBy('name_'.$locale)
            ->limit(50)
            ->get()
            ->map(function ($settlement) {
                return [
                    'id' => $settlement->id,
                    'text' => $settlement->name,
                ];
            })
            ->values()
            ->all();
            
        return response()->json($settlements);
    }
}
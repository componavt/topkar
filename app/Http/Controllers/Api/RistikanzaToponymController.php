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

    public function show(int $id)
    {
        $toponym = Toponym::query()
            ->whereId($id)
            ->whereIn('district_id', Toponym::nLadogaDistricts)
            ->where('geotype_id', 63)
            ->with([
                'lang',
                'geotype',
                'ethnosTerritory',
                'etymologyNation',

                //'topnames.lang',
                //'wrongnames.lang',

                //'settlement1926',
                //'settlements',

                //'texts',

                'sourceToponyms',

                'structs.structhier.parent',

                'events',
                //'events.settlements',
                //'events.settlements1926',
                //'events.informants',
                //'events.recorders',
            ])
            ->firstOrFail();

        return response()->json($this->toponymData($toponym));
    }

    protected function toponymData(Toponym $toponym): array
    {
        return [
            'id' => $toponym->id,
            'name' => $toponym->name,

            'lang' => optional($toponym->lang)->short,
            'wd_url' => $toponym->wdURL(),

            'topnames' => $toponym->topnamesWithLangs(),
            'wrongnames' => $toponym->wrongnamesWithLangs(),

            'location' => $toponym->location,
            'location_1926' => $toponym->location1926,

            'main_info' => $toponym->main_info,
            'etymology_nation' => optional($toponym->etymologyNation)->name,
            'caseform' => $toponym->caseform,

            'etymology' => $toponym->etymology,
            'legend' => $toponym->legend,

            'sources' => $toponym->sourceToponyms
                ->map(function ($sourceToponym) {
                    return [
                        'mention' => $sourceToponym->mention,
                        'source' => $sourceToponym->sourceToString(0, 1),
                    ];
                })
                ->values()
                ->all(),

            'structs' => $toponym->structs
                ->map(function ($struct) {
                    return [
                        'name' => optional($struct)->name,
                        'group' => $struct && $struct->structhier
                            ? $struct->structhier->parent->name . ' ' .
                            mb_strtolower($struct->structhier->name)
                            : null,
                    ];
                })
                ->values()
                ->all(),

            'events' => $toponym->events
                ->map(function ($event) {
                    return [
                        'place' => trim(
                            $event->settlementsToString() .
                                ($event->settlementsToString() && $event->settlements1926ToString() ? ', ' : '') .
                                $event->settlements1926ToString()
                        ),
                        'date' => $event->date,
                        'informants' => $event->informantsToString(),
                        'recorders' => $event->recordersToString(),
                    ];
                })
                ->values()
                ->all(),

            'map' => $this->mapData($toponym),
        ];
    }

    public function mapData(Toponym $toponym)
    {
        $object = $toponym->objOnMap();

        if (!$object) {
            return null;
        }

        return [
            'latitude' => $object->latitude,
            'longitude' => $object->longitude,
            'zoom' => 11,
            'color' => $object === $toponym ? 'blue' : 'grey',
        ];
    }

    public function oikonymFormValues()
    {
        $nladoga_districts = Toponym::nLadogaDistricts;
        $nladoga_region1926 = Toponym::nLadogaRegion1926;

        return response()->json([
            'district_values' => array_intersect_key(District::getList(), array_flip($nladoga_districts)),
            //'region1926_ids' => (array)$nladoga_region1926,
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

        $sname = '%' . trim((string)($params['q'] ?? '')) . '%';
        $year_from = $params['year_from'] ?? null;
        $year_to = $params['year_to'] ?? null;

        if ($year_from && $year_to && $year_from > $year_to) {
            return response()->json([]);
        }

        $sources = Source::query()
            ->when($sname !== '', function ($query) use ($sname) {
                $query->where('name_en',  'like',  $sname)
                    ->orWhere('name_ru', 'like',  $sname)
                    ->orWhere('short_en', 'like',  $sname)
                    ->orWhere('short_ru', 'like',  $sname);
            })
            ->when($year_from, function ($query) use ($year_from) {
                $query->where('year', '>=', $year_from);
            })
            ->when($year_to, function ($query) use ($year_to) {
                $query->where('year', '<=', $year_to);
            })
            ->orderBy('name_' . $locale)
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
            'districts' => ['nullable', 'array'],
            'districts.*' => ['integer', 'min:1'],
        ]);

        $settlement_name = '%' . trim((string)($params['q'] ?? '')) . '%';
        $districts = array_remove_null($params['districts'] ?? []);
        if (!sizeof($districts)) {
            $districts = Toponym::nLadogaDistricts;
        }
        //return response()->json($districts);

        $settlements = Settlement::query()
            ->when($settlement_name !== '', function ($query) use ($settlement_name) {
                $query->where(function ($q) use ($settlement_name) {
                    $q->where('name_en', 'like', $settlement_name)
                        ->orWhere('name_ru', 'like', $settlement_name);
                });
            })
            ->when(sizeof($districts), function ($query) use ($districts) {
                $query->whereIn('id', function ($q) use ($districts) {
                    $q->select('settlement_id')->from('district_settlement')
                        ->whereIn('district_id', $districts);
                });
            })
            //        return response()->json(to_sql($settlements));
            ->orderBy('name_' . $locale)
            //            ->limit(50)
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

    public function oikonymSettlements1926(Request $request)
    {
        $locale = app()->getLocale();
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'districts' => ['nullable', 'array'],
            'districts.*' => ['integer', 'min:1'],
            'selsovets' => ['nullable', 'array'],
            'selsovets.*' => ['integer', 'min:1'],
        ]);

        $settlement_name = '%' . trim((string)($params['q'] ?? '')) . '%';
        $districts1926 = array_remove_null($params['districts'] ?? []);
        if (empty($districts1926)) {
            $districts1926 = District1926::whereIn('region_id', Toponym::nLadogaDistricts)->get('id')->toArray();
        }
        $selsovets1926 = array_remove_null($params['selsovets'] ?? []);
        if (empty($selsovets1926)) {
            $selsovets1926 = Selsovet1926::whereIn('district1926_id', $districts1926)->get('id')->toArray();
        }

        $settlements1926 = Settlement1926::query()
            ->when($settlement_name !== '', function ($query) use ($settlement_name) {
                $query->where(function ($q) use ($settlement_name) {
                    $q->where('name_en', 'like', $settlement_name)
                        ->orWhere('name_ru', 'like', $settlement_name);
                });
            })
            ->when(sizeof($selsovets1926), function ($q) use ($selsovets1926) {
                $q->whereIn('selsovet_id', $selsovets1926);
            })
            ->when(sizeof($districts1926), function ($query) use ($districts1926) {
                $query->whereIn('selsovet_id', function ($q) use ($districts1926) {
                    $q->select('id')->from('selsovets1926')
                        ->whereIn('district1926_id', $districts1926);
                });
            })
            ->orderBy('name_' . $locale)
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

        return response()->json($settlements1926);
    }

    public function oikonymSelsovets1926(Request $request)
    {
        $locale = app()->getLocale();
        $params = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'districts' => ['nullable', 'array'],
            'districts.*' => ['integer', 'min:1'],
        ]);

        $sname = '%' . trim((string)($params['q'] ?? '')) . '%';
        $districts1926 = array_remove_null($params['districts'] ?? []);
        if (empty($districts1926)) {
            $districts1926 = District1926::whereIn('region_id', Toponym::nLadogaDistricts)->get('id')->toArray();
        }

        $selsovets1926 = Selsovet1926::query()
            ->when($sname !== '', function ($query) use ($sname) {
                $query->where(function ($q) use ($sname) {
                    $q->where('name_en', 'like', $sname)
                        ->orWhere('name_ru', 'like', $sname);
                });
            })
            ->when(sizeof($districts1926), function ($q) use ($districts1926) {
                $q->whereIn('district1926_id', $districts1926);
            })
            ->orderBy('name_' . $locale)
            ->limit(50)
            ->get()
            ->map(function ($selsovet) {
                return [
                    'id' => $selsovet->id,
                    'text' => $selsovet->name,
                ];
            })
            ->values()
            ->all();

        return response()->json($selsovets1926);
    }
}

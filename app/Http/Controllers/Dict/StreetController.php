<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Redirect;
use Response;

use App\Models\Dict\Street;
use App\Models\Misc\Geotype;
use App\Models\Misc\Struct;
use App\Models\Misc\Structhier;

class StreetController extends Controller
{
    public $url_args = [];
    public $args_by_get = '';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware(
            'is_editor',
            ['except' => ['index', 'list', 'show']]
        );
        $this->url_args = Street::urlArgs($request);

        $this->args_by_get = search_values_by_URL($this->url_args);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $streets = Street::search($url_args);
        $streets = $streets->with('geotype')->paginate($this->url_args['portion']);
        $n_records = $streets->total();

        $geotype_values = Geotype::streetTypes();
        $sort_values = Street::sortList();
        $struct_values = Struct::getList();
        $structhier_values = Street::structhierList();

        return view(
            'dict.streets.index',
            compact(
                'geotype_values',
                'n_records',
                'sort_values',
                'streets',
                'struct_values',
                'structhier_values',
                'args_by_get',
                'url_args'
            )
        );
    }

    public function onMap(Request $request)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;
        $limit = 1000;
        if (empty($url_args['map_height'])) {
            $url_args['map_height'] = 1700;
        }

        /*        list($total_rec, $show_count, $objs, $limit, $bounds, $url_args)
            = Toponym::forMap($limit, $url_args);*/

        $geotype_values = Geotype::getList();
        $struct_values = Struct::getList();
        $structhier_values = Structhier::getGroupedList();
        $sort_values = Street::sortList();

        return view(
            'dict.streets.on_map',
            compact(
                /*                'bounds',
                'objs',
                'limit',*/
                'geotype_values',
                //                'show_count',
                'sort_values',
                'struct_values',
                'structhier_values',
                //                'total_rec',
                'args_by_get',
                'url_args'
            )
        );
    }

    /**
     * Validate request data
     */
    public function validateRequest(Request $request)
    {
        $allowed_types = implode(',', Street::Types);

        $this->validate($request, [
            'name_ru'  => 'required|string|max:150',
            'name_krl' => 'nullable|string|max:150',
            'name_fi' => 'nullable|string|max:150',
            'geotype_id' => 'nullable|integer|in:' . $allowed_types,
            'history' => 'nullable|string',
            'main_info' => 'nullable|string',
        ]);
        return $request->only(['name_ru', 'name_krl', 'name_fi', 'geotype_id', 'history', 'main_info']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $geotype_values = Geotype::streetTypes();
        $structhier_values = Street::structhierList();
        foreach (array_keys($structhier_values) as $structhier_id) {
            $struct_values[$structhier_id] = ['' => NULL] + Struct::getList(false, $structhier_id);
        }

        $action = 'create';

        return view(
            'dict.streets.modify',
            compact(
                'action',
                'geotype_values',
                'struct_values',
                'structhier_values',
                'args_by_get',
                'url_args'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $street = Street::storeData($this->validateRequest($request), $request);

        return Redirect::to(route('streets.show', $street) . ($this->args_by_get))
            ->withSuccess(trans('messages.created_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dict\Street  $street
     * @return \Illuminate\Http\Response
     */
    public function show(Street $street)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        return view(
            'dict.streets.show',
            compact('street', 'args_by_get', 'url_args')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dict\Street  $street
     * @return \Illuminate\Http\Response
     */
    public function edit(Street $street)
    {
        $args_by_get = $this->args_by_get;
        $url_args = $this->url_args;

        $geotype_values = Geotype::streetTypes();
        $structhier_values = Street::structhierList();
        foreach (array_keys($structhier_values) as $structhier_id) {
            $struct_values[$structhier_id] = ['' => NULL] + Struct::getList(false, $structhier_id);
        }

        $action = 'edit';

        return view(
            'dict.streets.modify',
            compact(
                'action',
                'geotype_values',
                'street',
                'struct_values',
                'structhier_values',
                'args_by_get',
                'url_args'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dict\Street  $street
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Street $street)
    {
        $street->updateData($this->validateRequest($request), $request);

        if ($street->isClean()) {
            return Redirect::to(route('streets.show', $street) . ($this->args_by_get))
                ->withWarning(trans('messages.edit_warning'));
        }

        $street->save();
        $street->logTouch();
        return Redirect::to(route('streets.show', $street) . ($this->args_by_get))
            ->withSuccess(trans('messages.updated_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dict\Street  $street
     * @return \Illuminate\Http\Response
     */
    public function destroy(Street $street)
    {
        $status_code = 200;
        $result = [];
        if ($street) {
            try {
                $name = $street->name;
                $street->remove();
                $result['message'] = trans('toponym.street_removed', ['name' => $name]);
            } catch (\Exception $ex) {
                $error = true;
                $status_code = $ex->getCode();
                $result['error_code'] = $ex->getCode();
                $result['error_message'] = $ex->getMessage();
            }
        } else {
            $error = true;
            $status_code = 400;
            $result['message'] = 'Request data is empty';
        }

        if ($error) {
            return Redirect::to(route('streets.index') . $this->args_by_get)
                ->withErrors($result['error_message']);
        } else {
            return Redirect::to(route('streets.index') . $this->args_by_get)
                ->withSuccess($result['message']);
        }
    }

    /**
     * Simple AJAX list for select dropdowns
     */
    public function sList(Request $request)
    {
        $search = $request->input('q');
        $geotype_id = $request->input('geotype_id');

        $streets = Street::orderBy('name_ru');

        if ($search) {
            $streets = $streets->where(function ($q) use ($search) {
                $q->where('name_ru', 'like', '%' . $search . '%')
                    ->orWhere('name_krl', 'like', '%' . $search . '%')
                    ->orWhere('name_fi', 'like', '%' . $search . '%');
            });
        }

        if ($geotype_id) {
            $streets = $streets->where('geotype_id', $geotype_id);
        }

        $streets = $streets->get();

        $out = [];
        foreach ($streets as $street) {
            $out[] = ['id' => $street->id, 'text' => $street->name];
        }

        return Response::json($out);
    }

    /**
     * Simple store for AJAX
     */
    public function simpleStore(Request $request)
    {
        $name = $request->input('name');

        if (!$name) {
            return Response::json(['error' => 'Name is required'], 400);
        }

        $street = Street::create([
            'name_ru' => $name,
        ]);

        return Response::json([
            'id' => $street->id,
            'text' => $street->name
        ]);
    }

    public function history(Street $street)
    {
        if (!$street) {
            return Redirect::to('/dict/street/')
                ->withErrors(trans('messages.record_not_exists'));
        }
        return view('dict.streets.history')
            ->with([
                'street' => $street,
                'args_by_get'    => $this->args_by_get,
                'url_args'       => $this->url_args,
            ]);
    }

    public function geometry(Street $street): JsonResponse
    {
        $streetName = trim((string) $street->name_ru);

        abort_if($streetName === '', 404, 'Street name is empty');

        $cacheKey = 'street-geometry:petrozavodsk:' . $street->id . ':' . md5($streetName);

        $geojson = Cache::remember($cacheKey, now()->addHours(12), function () use ($streetName) {
            $query = $this->buildOverpassQuery($streetName);

            $endpoints = [
                'https://overpass.kumi.systems/api/interpreter',
                'https://lz4.overpass-api.de/api/interpreter',
                'https://overpass-api.de/api/interpreter',
            ];

            foreach ($endpoints as $endpoint) {
                $response = Http::asForm()
                    ->timeout(45)
                    ->retry(2, 1500)
                    ->post($endpoint, [
                        'data' => $query,
                    ]);

                if ($response->successful()) {
                    return $this->overpassToGeoJson($response->json(), $streetName);
                }

                if (!in_array($response->status(), [429, 502, 504], true)) {
                    abort(502, 'Overpass request failed: HTTP ' . $response->status());
                }
            }

            abort(502, 'Overpass is temporarily unavailable');
        });

        return response()->json($geojson);
    }

    protected function buildOverpassQuery(string $streetName): string
    {
        $safeName = $this->escapeOverpassRegex($streetName);

        // Ищем внутри административной области Петрозаводска.
        // name~"...",i — без учёта регистра.
        // out geom — возвращает координаты way, чтобы рисовать линию.
        return <<<OVERPASS
[out:json][timeout:12];
area["name"="Петрозаводск"]["boundary"="administrative"]->.city;
(
  way["highway"]["name"~"{$safeName}",i](area.city);
);
out geom;
OVERPASS;
    }

    protected function escapeOverpassRegex(string $value): string
    {
        $value = trim($value);
        $value = preg_quote($value, '/');
        return str_replace('"', '\\"', $value);
    }

    protected function overpassToGeoJson(array $osmJson, string $streetName): array
    {
        $features = [];

        foreach (($osmJson['elements'] ?? []) as $el) {
            if (($el['type'] ?? null) !== 'way') {
                continue;
            }

            if (empty($el['geom']) || count($el['geom']) < 2) {
                continue;
            }

            $coordinates = [];
            foreach ($el['geom'] as $point) {
                if (!isset($point['lon'], $point['lat'])) {
                    continue;
                }

                $coordinates[] = [(float) $point['lon'], (float) $point['lat']];
            }

            if (count($coordinates) < 2) {
                continue;
            }

            $features[] = [
                'type' => 'Feature',
                'properties' => [
                    'osm_id' => $el['id'] ?? null,
                    'name' => $el['tags']['name'] ?? $streetName,
                    'highway' => $el['tags']['highway'] ?? null,
                ],
                'geometry' => [
                    'type' => 'LineString',
                    'coordinates' => $coordinates,
                ],
            ];
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];
    }
}

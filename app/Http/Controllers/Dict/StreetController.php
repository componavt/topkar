<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $locale = app()->getLocale();

        $streets = Street::search($url_args);
        $streets = $streets->with('geotype')->paginate($this->url_args['portion']);
        $n_records = $streets->total();

        $geotype_values = Geotype::streetTypes();
        $sort_values = Street::sortList();
        $struct_values = Struct::getList();
        $structhier_values = Structhier::getGroupedList();

        return view(
            'dict.streets.index',
            compact(
                'geotype_values',
                'locale',
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
            ->withSuccess(\Lang::get('messages.created_success'));
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
                ->withWarning(\Lang::get('messages.edit_warning'));
        }

        $street->save();
        $street->logTouch();
        return Redirect::to(route('streets.show', $street) . ($this->args_by_get))
            ->withSuccess(\Lang::get('messages.updated_success'));
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
                $result['message'] = \Lang::get('toponym.street_removed', ['name' => $name]);
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
                ->withErrors(\Lang::get('messages.record_not_exists'));
        }
        return view('dict.streets.history')
            ->with([
                'street' => $street,
                'args_by_get'    => $this->args_by_get,
                'url_args'       => $this->url_args,
            ]);
    }
}

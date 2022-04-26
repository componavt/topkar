<?php

namespace App\Http\Controllers\Aux;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

use App\Models\Aux\Geotype;

class GeotypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $geotypes = Geotype::all();
        return view('aux.geotypes.index', compact('geotypes'));
    }

    public function validateRequest(Request $request) {
        $this->validate($request, [
            'short_ru'  => 'max:32',
            'name_ru'  => 'required|max:64',
            'desc_ru'  => 'max:255',
            'short_en'  => 'max:32',
            'name_en'  => 'max:64',
            'desc_en'  => 'max:255',
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function simpleStore(Request $request)
    {
        $field = $request->locale == 'en' ? 'name_en' : 'name_ru';
        $this->validateRequest($request);
        $geotype = Geotype::where('name_ru', 'like', $request->name_ru)->first();
        if ($geotype) {
            return Response::json(['id'=>$geotype->id, 'name'=>$geotype->{$field}]);
        }
        $geotype = Geotype::where('short_ru', 'like', $request->short_ru)->first();
        if ($geotype) {
            return Response::json(['id'=>$geotype->id, 'name'=>$geotype->{$field}]);
        }
        $geotype = Geotype::where('name_en', 'like', $request->name_en)->first();
        if ($geotype) {
            return Response::json(['id'=>$geotype->id, 'name'=>$geotype->{$field}]);
        }
        $geotype = Geotype::where('short_en', 'like', $request->short_en)->first();
        if ($geotype) {
            return Response::json(['id'=>$geotype->id, 'name'=>$geotype->{$field}]);
        }
        $geotype = Geotype::create($request->all());
        return Response::json(['id'=>$geotype->id, 'name'=>$geotype->{$field}]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

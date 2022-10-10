<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopnameController extends Controller
{
    public function create(Request $request)
    {
        $id_name = 'new_topname'.(int)$request->num;
        $var_name='new_topname[]';
        return view('dict.topnames._create_edit', compact('id_name', 'var_name'));
    }
}

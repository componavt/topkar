<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function create(Request $request)
    {
        $num = $request->num;
        $var_name='new_sources';
        return view('dict.sources._create_edit', compact('num', 'var_name'));
    }
}

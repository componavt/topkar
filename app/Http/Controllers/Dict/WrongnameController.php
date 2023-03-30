<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Dict\Lang;

class WrongnameController extends Controller
{
    public function create(Request $request)
    {
        $num = (int)$request->num;
        $id_name = 'new_wrongname'.$num;
        $var_name='new_wrongname['.$num.']';
        $lang_values = [''=>NULL] + Lang::getList();
        return view('dict.wrongnames._create_edit', 
                compact('id_name', 'lang_values', 'var_name'));
    }
}

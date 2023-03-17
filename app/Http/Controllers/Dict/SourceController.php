<?php

namespace App\Http\Controllers\Dict;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Dict\Source;

class SourceController extends Controller
{
    public function index() {
        $sources = Source::groupBy('source')
                         ->selectRaw('source, count(*) as count')
                         ->orderBy('count', 'desc')
                         ->orderBy('source')
                        ->get();
        return view('dict.sources.index', compact('sources'));        
    }
    
    public function create(Request $request)
    {
        $num = $request->num;
        $var_name='new_sources';
        return view('dict.sources._create_edit', compact('num', 'var_name'));
    }
}

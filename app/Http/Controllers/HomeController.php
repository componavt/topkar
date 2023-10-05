<?php

namespace App\Http\Controllers;

//use Redirect;

use App\Models\Dict\Toponym;

class HomeController extends Controller
{
    public function index()
    {
        $toponym = Toponym::whereNotNull('latitude')->whereNotNull('longitude')
                ->inRandomOrder()->first();
        return view('welcome', compact('toponym'));
    }
    
}

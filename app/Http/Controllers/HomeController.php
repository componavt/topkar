<?php

namespace App\Http\Controllers;

//use Redirect;
use Illuminate\Support\Facades\View;

use App\Models\Dict\Toponym;

class HomeController extends Controller
{
    public function index()
    {
        $toponym = Toponym::whereNotNull('latitude')->whereNotNull('longitude')
                ->inRandomOrder()->first();
        return view('welcome', compact('toponym'));
    }

    public function page($page) {
        if (View::exists('pages.'.$page)) {
            return view('pages.'.$page);        
        }
    }    
}

<?php

//use Illuminate\Support\Str;

use App\Models\Team;

// to_link("Hello, World!", "/dict/") -> <a href="/ru/dict/">Hello, World!</a>
if (! function_exists('to_link')) {
    function to_link($str, $link)
    {
        return '<a href="'.LaravelLocalization::localizeURL($link).'">'.$str.'</a>';            

    }
}

/*
if (! function_exists('array_to_string')) {
    function array_to_string($arr, $b_div='<b>', $e_div='</b>') {
        $out = '';
        $count = 1;
        foreach ($arr as $p=>$c) { 
            $out .= $b_div."$p".$e_div.": $c";
            if ($count<sizeof($arr)) {
                $out .= ", ";
            }
            $count++;
        }   
        return $out;
    }
}*/

// Converts the array $url_args (name->value) to String
// Usage: 
// $this->args_by_get = search_values_by_URL($this->url_args);
if (! function_exists('search_values_by_URL')) {
    function search_values_by_URL(array $url_args=NULL)
    {
        $out = http_build_query($url_args);
        return $out ? '?'.$out : '';
    }
}


// extracts some parameters from object Request into array $url_args
if (! function_exists('url_args')) {
    function url_args($request, $limit_min=10) {
        $url_args = [
            'portion' => (int)$request->input('portion'), // number of records per page
            'page'      => (int)$request->input('page'),      // number of page
        ];
        if (!$url_args['page']) {
            $url_args['page'] = 1;
        }
        
        if ($url_args['portion']<=0) {
            $url_args['portion'] = $limit_min;
        } elseif ($url_args['portion']>1000) {
            $url_args['portion'] = 1000;
        }   
        return $url_args;
    }
}

if (! function_exists('user_is_admin')) {
    function user_is_admin()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        $team = Team::whereName('TopKar editors')->first();
        if ($user->belongsToTeam($team) && $user->hasTeamRole($team, 'admin')) {
            return true;
        }
//User::checkAccess('dict.edit');        
    }
}

if (! function_exists('user_can_edit')) {
    function user_can_edit()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        $team = Team::whereName('TopKar editors')->first();
        if ($user->belongsToTeam($team) && 
            ($user->hasTeamRole($team, 'admin') || $user->hasTeamRole($team, 'editor'))) {
            return true;
        }
//        return true;//User::checkAccess('dict.edit');
    }
}

if (! function_exists('to_sql')) {
    function to_sql($query)
    {
        return vsprintf(str_replace(array('?'), array('\'%s\''), $query->toSql()), $query->getBindings());            

    }
}

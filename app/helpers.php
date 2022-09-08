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

if (! function_exists('mb_ucfirst')) {
    function mb_ucfirst($string, $encoding = 'UTF-8'){
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
}

if (! function_exists('to_search_form')) {
    function to_search_form($word) {
        $word = str_replace('’','',$word);
        if (preg_match("/^\-(.+)$/u", $word, $regs)) {
            $word = $regs[1];
        }
        $word = mb_strtolower($word);
        return $word;
    }
}

if (! function_exists('remove_spaces')) {
    function remove_spaces($word) {
        $word = trim($word);
        $word = preg_replace("/\s{2,}/", " ", $word);
        return $word;
    }
}

if (! function_exists('to_right_form')) {
    function to_right_form($word) {
        $word = trim($word);
        $word = remove_spaces($word);
        $word = preg_replace("/['´`΄]+/u", "’", $word);
        return $word;
    }
}

if (! function_exists('array_remove_null')) {
    function array_remove_null($arr)
    {
//        return array_filter($arr, fn($value)=>!is_null($value) && $value !== '');            
        return array_filter($arr, function ($value) { 
            return !is_null($value) && $value !== '';
        });
    }
}

if (! function_exists('mb_ucfirst')) {
    function mb_ucfirst($str) {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }
}

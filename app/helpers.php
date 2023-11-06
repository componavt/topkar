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

if (! function_exists('to_route')) {
    function to_route($str, $route, $obj=null, $args_by_get='', $class='')
    {
        $link = $obj ? route($route, $obj) : route($route);
        return '<a href="'.$link.$args_by_get.'"'.
               ($class ? ' class="'.$class.'"' : '').'>'.$str.'</a>';            
    }
}

if (! function_exists('to_show')) {
    function to_show($str, $model, $obj, $args_by_get='')
    {
        return to_route($str, plural_from_model($model).'.show', $obj, $args_by_get);            
    }
}

if (! function_exists('plural_from_model')) {
    function plural_from_model($model)
    {
        $plural = Str::plural(class_basename($model));
        return Str::camel($plural);
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
        $out = http_build_query(remove_empty(remove_default($url_args)));
        return $out ? '?'.$out : '';
    }
}

if (! function_exists('remove_empty')) {
    function remove_empty(array $url_args=NULL)
    {
        foreach ( $url_args as $k=>$v ) {
            if (!$v || is_array($v) && (!sizeof($v) || sizeof($v)==1 && isset($v[1]) && !$v[1])) {
                unset($url_args[$k]);
            } 
        }
        return $url_args;
    }
}

if (! function_exists('remove_empty_elems')) {
    function remove_empty_elems(array $url_args=NULL)
    {
        foreach ( $url_args as $k=>$v ) {
            if (is_array($v) && sizeof($v)==1 && array_key_exists(0,$v) && empty($v[0])) { // 
                $url_args[$k] = [];
            } 
        }
        return $url_args;
    }
}

if (! function_exists('remove_default')) {
    function remove_default(array $url_args=NULL)
    {
        if (array_key_exists('limit_num', $url_args) && $url_args['limit_num']==10) {
            unset($url_args['limit_num']);
        }
        if (array_key_exists('page', $url_args) && $url_args['page']==1) {
            unset($url_args['page']);
        }
        return $url_args;
    }
}

if (! function_exists('request_arr')) {
    function request_arr($arr)
    {
        return !empty($arr) ? remove_empty((array)$arr) : [];
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
        $special_chars = [
            '’' => '',
            'а́' => 'а', 'е́' => 'е', 'и́' => 'и', 'о́' => 'о', 'у́' => 'у', 'ы́' => 'ы', 'э́' => 'э', 'ю́' => 'ю', 'я́' => 'я',
            'А́' => 'а', 'Е́' => 'е', 'И́' => 'и', 'О́' => 'о', 'У́' => 'у', 'Ы́' => 'ы', 'Э́' => 'э', 'Ю́' => 'ю', 'Я́' => 'я'
        ];
        foreach ($special_chars as $from => $to) {
            $word = str_replace($from,$to,$word);
        }
    /*    if (preg_match("/^\-(.+)$/u", $word, $regs)) {
            $word = $regs[1];
        }*/
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
        return array_filter((array)$arr, function ($value) { 
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

if (! function_exists('number_with_space')) {
    function number_with_space($num) {
        return number_format($num, 0, '', ' ');
    }
}

if (! function_exists('trans_with_choice')) {
    function trans_with_choice($var, $count) {
        return trans_choice($var, 
            $count%10==0 ? $count : ($count%100>20 ? $count%10  : $count%100), 
            ['count'=>number_with_space($count, 0, ',', ' ')]);
    }
}

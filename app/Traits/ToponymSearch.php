<?php namespace App\Traits;

trait AdvertSearch
{
    use \App\Traits\Methods\search\byName;
    
    /** Gets array of search parameters.
     * 
     * @param type $request
     * @return type
     */
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'search_title' => $request->input('search_title'),
                    'with_trashed' => (int)$request->input('with_trashed'),
                ];
        return $url_args;
    }
    
    /** Search district by various parameters. 
     * 
     * @param array $url_args
     * @return type
     */
    public static function search(Array $url_args) {
        
        $recs = self::orderBy('data','desc');
        
        if ($url_args['with_trashed']) {
            $recs = $recs->withTrashed();
        }  
        
        if ($url_args['search_title']) {
            $recs = $recs->where('title', 'like', $url_args['search_title']);
        }  
        
        return $recs;
    }    
}
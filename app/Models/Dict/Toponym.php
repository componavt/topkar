<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Settlement1926;
use App\Models\Aux\Geotype;
use App\Models\Aux\EtymologyNation;
use App\Models\Aux\EthnosTerritory;
use App\Models\Aux\Struct;

class Toponym extends Model
{
    use HasFactory;
    //use \App\Traits\Methods\getNameAttribute;
    
    
    /**
     * Get the settlement1926 which contains this toponym
     * One To Many (Inverse) / Belongs To.
     * One settlement1926 and many toponyms.
     * https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function settlement1926()
    {
        return $this->belongsTo(Settlement1926::class);
    }
    
    /**
     * Get the district which contains this toponym
     * One To Many (Inverse) / Belongs To, https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function district()
    {
        //                                       'foreign_key', 'owner_key'
        return $this->belongsTo(District::class, 'DISTRICT_ID', 'id');
    }
    
    /**
     * Get the geotype which contains this toponym
     * One To Many (Inverse) / Belongs To
     */
    public function geotype()
    {
        //                                      'foreign_key','owner_key'
        return $this->belongsTo(Geotype::class, 'geotype_id', 'id');
    }
    
    /**
     * Get the etymology nation name which contains this toponym
     * One To Many (Inverse) / Belongs To
     */
    public function etymologyNation()
    {
        return $this->belongsTo(EtymologyNation::class);
    }
    
    /**
     * Get the geotype which contains this toponym
     * One To Many (Inverse) / Belongs To
     */
    public function ethnosTerritory()
    {
        return $this->belongsTo(EthnosTerritory::class);
    }
    
    /**
     * The structures that belong to the toponym. (many to many relation)
     */
    public function structs()
    {
        return $this->belongsToMany(Struct::class);
    }
    
    
    
    /**
     * Get 'Region, district, SETTLEMENT (String)' concatenated by comma.
     */
    public function getLocationAttribute()
    {
        return $this->getRegionNameAttribute().', '. 
               $this->getDistrictNameAttribute().', '. 
               $this->getSettlementNameAttribute();
    }
    
    public function getRegionNameAttribute()
    {

        return optional(optional($this->district)->region)->name; 
    }
    
    public function getDistrictNameAttribute()
    {
        if( $this->district ) 
        { 
            return $this->district->name; 
        }
        return "";
    }
    
    public function getSettlementNameAttribute()
    {
        // TODO: to change string to settlement_id,
        // to create tables (pink colors): settlements
        return $this->SETTLEMENT;
    }
    
    /**
     * Get 'Region, district1926, selsovet1926, settlement1926' concatenated by comma.
     */
    public function getLocation1926Attribute()
    {
        return $this->getRegion1926NameAttribute().', '. 
               $this->getDistrict1926NameAttribute().', '. 
               $this->getSelsovet1926NameAttribute().', '.
               $this->getSettlment1926NameAttribute();
    }
    
    /**
     * Get name of region via selsovet1926->district1926.
     * If name is absent, then return empty string.
     */
    public function getRegion1926NameAttribute()
    {
        if( $this->settlement1926 &&
            $this->settlement1926->selsovet1926 &&
            $this->settlement1926->selsovet1926->district1926 &&
            $this->settlement1926->selsovet1926->district1926->region ) 
        { 
            return $this->settlement1926->selsovet1926->district1926->region->name; 
        }
        
        return "";
    }
    
    public function getDistrict1926NameAttribute()
    {
        if( $this->settlement1926 &&
            $this->settlement1926->selsovet1926 &&
            $this->settlement1926->selsovet1926->district1926 ) 
        { 
            return $this->settlement1926->selsovet1926->district1926->name; 
        }
        
        return "";
    }
    
    // selsovet1926->name
    public function getSelsovet1926NameAttribute()
    {
        if( $this->settlement1926 &&
            $this->settlement1926->selsovet1926 ) 
        { 
            return $this->settlement1926->selsovet1926->name; 
        }
        
        return "";
    }
    
    public function getSettlment1926NameAttribute()
    {
        if( $this->settlement1926 ) 
        { 
            return $this->settlement1926->name; 
        }
        
        return "";
    }
    
    /** Gets array of search parameters.
     * 
     * @param type $request
     * @return type
     */
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'search_toponym'    => $request->input('search_toponym'),
                    'search_region'     => (int)$request->input('search_region'),
                    'search_district'   => (int)$request->input('search_district'),
                    'search_settlement' => $request->input('search_settlement'),

            /*
                    'search_district'  => (array)$request->input('search_district'),
                    'search_lang'     => (int)$request->input('search_lang'),
                    'search_place'    => (array)$request->input('search_place'),
                    'search_region' => $request->input('search_region'),
                    
                    'search_text'     => $request->input('search_text'),*/
                ];
        
        return $url_args;
    }
    
    
    /** Search toponym by various parameters. 
     * 
     * @param array $url_args
     * @return type
     */
    public static function search(Array $url_args) {
        
        $toponyms = self::orderBy('name');
        //$toponyms = self::searchByPlace($toponyms, $url_args['search_place'], $url_args['search_district'], $url_args['search_region']);
        
        if ($url_args['search_toponym']) {
            $toponyms = $toponyms->where('name','LIKE',$url_args['search_toponym']);
        } 
        
        if ($url_args['search_settlement']) {
            $toponyms = $toponyms->where('SETTLEMENT','LIKE',$url_args['search_settlement']);
        }
        
        if ($url_args['search_district']) {
            $toponyms = $toponyms->where('DISTRICT_ID',$url_args['search_district']);
        } 
        
        $toponyms = self::searchByRegion($toponyms, $url_args['search_region']);
//dd($toponyms->toSql());                                

        return $toponyms;
    }
    
    /** Search toponym by region. 
     * 
     * @param array $url_args
     * @return type
     */
    public static function searchByRegion($toponyms, $search_region) {
        
        if(! $search_region) {
            return $toponyms;
        }
        
        $toponyms = $toponyms->whereIn('district_id', function($query) use ($search_region) {
            $query -> select ('id') -> from ('districts') 
                    -> whereRegionId( $search_region );
        });
        
//dd($toponyms->toSql());                                

        return $toponyms;
    }

    
}

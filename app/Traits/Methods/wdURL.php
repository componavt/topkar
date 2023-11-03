<?php namespace App\Traits\Methods;

trait wdURL
{
    /** Gets Wikidata URL from Wikidata ID.
     * 55659275 -> <a href="https://www.wikidata.org/wiki/Q55659275">Q55659275</a>
     * @return string
     */
    public function wdURL($text=NULL)
    {  
        if(!$this->wd) { return ""; }
        
        return "<a href=\"https://www.wikidata.org/wiki/Q".
                $this->wd.'">'. ($text ?? 'Q'.$this->wd)."</a>";
    }
    
}
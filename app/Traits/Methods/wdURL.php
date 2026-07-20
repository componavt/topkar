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
        
        return '<a href="' . e($this->wikidataUrl()) . '"'
            . ' target="_blank" rel="noopener noreferrer">'
            . e($text ?? 'Q' . $this->wd)
            . '</a>';

    }
    
    public function wikidataUrl(): ?string
    {
        if (!$this->wd) {
            return null;
        }

        return 'https://www.wikidata.org/wiki/Q' . $this->wd;
    }
    
}
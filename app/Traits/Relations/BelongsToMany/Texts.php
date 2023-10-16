<?php namespace App\Traits\Relations\BelongsToMany;

use App\Models\Vepkar\Text;

trait Texts
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function texts(){
        return $this->belongsToMany(Text::class,'topkar.text_toponym');
    }    

    public function textIDs($div = '; '){
        return join($div, $this->texts()->pluck('id')->toArray());
    }    
    
    public function textUrls($div = '; '){
        $out = [];
        foreach ($this->texts as $text) {
            $out[] = '<a href="'.env('VEPKAR_URL').app()->getLocale().'/corpus/text/'.$text->id.'">"'.$text->title.'"</a>';
        }
        return join($div, $out);
    }    
    
}


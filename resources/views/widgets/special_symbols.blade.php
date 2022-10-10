<?php
    $symb_list = ['ä'=>'','ö'=>'','ü'=>'','č'=>'','š'=>'','ž'=>'','’'=>''];
?>
<a class='special-symbols-link' type='button' onClick="toggleSpecial('{{$id_name}}-special')">ä</a>

<div id='{{$id_name}}-special' class='special-symbols'>
    <div class="special-symbols-header">
        <div class="special-symbols-close">
            <i class="fa fa-times" onclick="closeSpecial('{{$id_name}}-special')"></i>
        </div>
    </div>
    <div class="special-symbols-body">
    @foreach($symb_list as $sym=>$sym_title)
    <input class='special-symbol-b' title='{{$sym_title}}' type='button' value='{{$sym}}' onClick='insertSymbol("{{$sym}}","{{$id_name}}")'>
    @endforeach
    </div>
</div>
{{--, '́а́', '́е́', '́и́', '́о́', '́у́', '́ы́', '́э́', '́ю́', '́я́','́А','́Е','́И','́О','́У','́Ы','́Э','́Ю','́Я'́--}}
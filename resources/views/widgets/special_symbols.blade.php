<?php
    $symb_list = [
//        'ä'=>'','ö'=>'','ü'=>'','č'=>'','š'=>'','ž'=>'','’'=>''
        'ä', 'ö', 'ü', 'č','š', 'ž','’'];
    if (isset($full_special_list) && $full_special_list) {
        array_push($symb_list,
        'а́', 'е́', 'и́', 'о́', 'у́', 'ы́', 'э́', 'ю́', 'я́',
        'А́', 'Е́', 'И́', 'О́', 'У́', 'Ы́', 'Э́', 'Ю́', 'Я́'
        );
    }
?>
<a class='special-symbols-link' type='button' onClick="toggleSpecial('{{$id_name}}-special')">ä</a>

<div id='{{$id_name}}-special' class='special-symbols'>
    <div class="special-symbols-header">
        <div class="special-symbols-close">
            <i class="fa fa-times" onclick="closeSpecial('{{$id_name}}-special')"></i>
        </div>
    </div>
    <div class="special-symbols-body">
    @foreach($symb_list as $sym)
    <input class='special-symbol-b' type='button' value="{{$sym}}" onClick='insertSymbol("{{$sym}}","{{$id_name}}")'>
    {{--title='{{$sym_title}}'--}}
    @endforeach
    </div>
</div>
́
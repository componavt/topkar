        @foreach($all_history as $time => $histories )
<?php 
$dt = \Carbon\Carbon::parse($time);
//dd($all_history);
$user = $histories[0]->userResponsible() ? \App\Models\User::find($histories[0]->userResponsible()->id) : null; 
$histories = $histories->sortBy('id');
$history_strings = [];
$diffConfig = new Caxy\HtmlDiff\HtmlDiffConfig();
foreach($histories as $history) {
    $fieldName = $history->fieldName();
    if (empty($history->field_name)) {
        $history->field_name = trans('history.'.$fieldName.'_a');
    }    
    
    if ($fieldName == 'created_at') :                          // создан новый экземпляр
        if (!empty($history->what_created)):
            $history_strings[] = trans('messages.created'). ' '
                               . $history->what_created;
        endif;
        
    elseif (empty($history->old_value)) :                     // добавлен атрибут
        $history_strings[] = trans('messages.created'). ' '
                           . $history->field_name .': <b>'
                           . $history->new_value.'</b>';
    
    elseif (!empty($history->old_value) && empty($history->new_value)) :  // удален атрибут
        $history_strings[] = trans('messages.deleted'). ' '
                           . $history->field_name .'</b>';

//    elseif ($fieldName == 'settlement_id') :                            // фиктивное поле
        
    
    elseif ($fieldName == 'main_info') :
            $htmlDiff = HtmlDiff::create($history->old_value, $history->new_value,$diffConfig);
            $history_strings[] = trans('messages.changed'). ' '
                               . $history->field_name. '<br>'.$htmlDiff->build();
    else :
            $history_strings[] = trans('messages.changed'). ' '
                               . $history->field_name. '<br>'
                               . trans('messages.from'). ' ' 
                               . ' <span class="old-value">'. $history->old_value. '</span><br>' 
                               . trans('messages.to'). ' '
                               . '<span class="new-value">'. $history->new_value. '</span>';
    endif;
}
?>
            @if (sizeof($history_strings))
        <p>
            <span class="date">{{ $dt->formatLocalized(trans('general.datetime_format')) }}</span>
            {{ $user ? $user->full_name : '' }} 
            <ul>
            @foreach($history_strings as $history)
                <li>{!! $history !!}</li>
            @endforeach
            </ul>
        </p>
            @endif
        @endforeach
        
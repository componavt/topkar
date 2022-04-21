    <div class='top-links'>        
        <a href="{{ route('toponyms.show', $toponym) }}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a>
        | <a href="{{ route('toponyms.index') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.back_to_list')) }}</a>
        @if (user_dict_edit())
            | <a href="{{ route('toponyms.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>

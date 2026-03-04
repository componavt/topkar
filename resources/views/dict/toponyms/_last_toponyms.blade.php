        @foreach ($items as $cr_date =>$date_items)
        <p class="date">{{$cr_date}}</p>
            @foreach ($date_items as $item)
            <div class="date-b">
                <div class="time">{{ $item->created_at->formatLocalized("%H:%M") }},
                    @if (!empty($item->user))<small>{{ $item->user }}</small>@endif
                </div>
                <div class="event">
                    <a href="{{ route(($item instanceof App\Models\Dict\Street ? 'street' : 'toponym'). 's.show', $item) }}">{{ $item->name }}</a>,
                    <i>@if (!empty($item->geotype)) {{ $item->geotype->name }} @endif</i>
                </div>
            </div>
            @endforeach
        @endforeach

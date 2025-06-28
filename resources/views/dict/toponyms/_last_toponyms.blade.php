        @foreach ($toponyms as $cr_date =>$dtoponyms)
        <p class="date">{{$cr_date}}</p>
            @foreach ($dtoponyms as $toponym)
            <div class="date-b">
                <div class="time">{{ $toponym->created_at->formatLocalized("%H:%M") }},
                    @if (!empty($toponym->user))<small>{{ $toponym->user }}</small>@endif
                </div>
                <div class="event">
                    <a href="{{ route('toponyms.show', $toponym) }}">{{ $toponym->name }}</a>, 
                    <i>@if (!empty($toponym->geotype)) {{ $toponym->geotype->name }} @endif</i>
                </div>
            </div> 
            @endforeach
        @endforeach

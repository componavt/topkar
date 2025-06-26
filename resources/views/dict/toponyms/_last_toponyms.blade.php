        @foreach ($toponyms as $cr_date =>$dtoponyms)
        <p class="date">{{$cr_date}}</p>
            @foreach ($dtoponyms as $toponym)
            <div class="date-b">
                <div class="time">{{ $toponym->created_at->formatLocalized("%H:%M") }}</div>
                <div class="event">
                    <a href="{{ route('toponyms.show', $toponym) }}">{{ $toponym->name }}</a> 
                    (@if (!empty($toponym->user)){{ $toponym->user }}@endif), <i>@if (!empty($toponym->geotype)) {{ $toponym->geotype->name }} @endif</i>
                </div>
            </div> 
            @endforeach
        @endforeach

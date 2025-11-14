@if(!empty($items_by_date))
    <div id="details-{{ $prefix . Str::slug($title) }}" class="detail-list">
    @foreach ($items_by_date as $date_info)
        <div class="detail-r">
            <div class="date">
                {{ $date_info['formatted_date'] }}
            </div>
        @foreach ($date_info['items'] as $item)
                <div style="margin-left: 10px;">
                    <span class="time">{{ $item['time'] }}</span>@if($item['url'])
                        <a href="{{ $item['url'] }}" target="_blank">{{ $item['name'] }}</a>@else
                        {{ $item['name'] }}@endif, {{ isset($item['fields']) ? ' поля: '.$item['fields'] : $item['type'] }}
                </div>
        @endforeach
        </div>
    @endforeach
    </div>
@endif
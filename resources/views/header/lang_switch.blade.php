{{-- Language switcher in navigation bar (right side) --}}
@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    @if ($localeCode != LaravelLocalization::getCurrentLocale())
    <li>
        <a class="nav-link" rel="alternate" hreflang="{{$localeCode}}" href="{{LaravelLocalization::getLocalizedURL($localeCode) }}">
        {{-- Thin space &thinsp; between flag and language name. --}}
        <span class="lang-sm lang-lbl" lang="{{$localeCode}}">&thinsp;</span>
            {{-- $properties['native'] --}}
        </a>
    </li>
    @endif
@endforeach
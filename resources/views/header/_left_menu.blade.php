<nav id="left-menu" class="navbar navbar-default">
    @include('header._collapsed_hamburger')

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
        <ul class="nav navbar-nav">
          <li class="nav-item dropdown" id='menu1'>
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
              {{ trans('navigation.about_project') }} <!--span class="caret"></span-->
            </a>
            <ul class="dropdown-menu" role="menu" id='menu1-sub'>
              <li><a class="dropdown-item" href="/">{{ trans('navigation.start') }}</a></li>
              <li><a class="dropdown-item" href="/stats/">{{ trans('navigation.stats') }}</a></li>
            @foreach (['participants', 'publications', 'sources', 'manual', 'how_to_cite'] as $v)
              <li><a class="dropdown-item" href="{{ route('pages', $v) }}">{{ trans('navigation.'.$v) }}</a></li>
            @endforeach
            </ul>
          </li>

          <li class="nav-item dropdown" id='menu2'>
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
              {{ trans('navigation.toponyms') }} 
            </a>
            <ul class="dropdown-menu" role="menu" id='menu2-sub'>
              <li><a class="dropdown-item" href="{{ route('toponyms.index') }}">{{ trans('navigation.search') }}</a></li>
            @foreach (['with_wrongnames', 'with_wd', 'with_legends', 'nladoga'] as $v)
              <li><a class="dropdown-item" href="{{ route('toponyms.'.$v) }}">{{ trans('navigation.'.$v) }}</a></li>
            @endforeach
              <li><a class="dropdown-item" href="#">{{ trans('navigation.last_created') }}</a></li>
            @if (user_can_edit()) 
                @foreach (['duplicates', 'link_to_settl'] as $v)
                  <li><a class="dropdown-item" href="{{ route('toponyms.'.$v) }}">{{ trans('navigation.'.$v) }}</a></li>
                @endforeach
              <li><a class="dropdown-item" href="{{ route('toponyms.list_for_export') }}">{{ trans('navigation.export_toponyms') }}</a></li>
            @endif
            </ul>
          </li>
          <li class="nav-item dropdown" id='menu3'>
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
              {{ trans('navigation.auxiliaries') }} 
            </a>
            <ul class="dropdown-menu" role="menu" id='menu3-sub'>
            @foreach (['regions', 'districts', 'settlements', 'districts1926', 'selsovets1926', 'settlements1926', 
                         'geotypes', 'informants', 'recorders', 'structs', 'sources'] as $v)
              <li><a class="dropdown-item" href="{{ route($v.'.index') }}">{{ trans('navigation.'.$v) }}</a></li>
            @endforeach
            </ul>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('toponyms.with_coords') }}" aria-expanded="false">{{ trans('navigation.map') }}</a>
          </li>
        </ul>
    </div>
</nav>

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
              @foreach (['participants', 'publications', 'sources', 'stats', 'manual', 'how_to_cite'] as $v)
              <li><a class="dropdown-item" href="#">{{ trans('navigation.'.$v) }}</a></li>
              @endforeach
            </ul>
          </li>

          <li class="nav-item dropdown" id='menu2'>
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
              {{ trans('navigation.toponyms') }} 
            </a>
            <ul class="dropdown-menu" role="menu" id='menu2-sub'>
              <li><a class="dropdown-item" href="{{ route('toponyms.index') }}">{{ trans('navigation.search') }}</a></li>
              @foreach (['with_wrongnames', 'with_wd', 'with_legends'] as $v)
              <li><a class="dropdown-item" href="{{ route('toponyms.'.$v) }}">{{ trans('navigation.'.$v) }}</a></li>
              @endforeach
              <li><a class="dropdown-item" href="#">{{ trans('navigation.last_created') }}</a></li>
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

<nav id="right-menu" class="navbar navbar-default">
    @include('header._collapsed_hamburger')

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
        <ul class="nav navbar-nav">
            
            <li class="nav-item">
        @if ('en' == LaravelLocalization::getCurrentLocale())
                <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL('ru') }}" aria-expanded="false">TopKar in Russian</a>
        @else
                <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL('en') }}" aria-expanded="false">TopKar in English</a>
        @endif
            </li>
            <li class="nav-item dropdown" id='menu4'>
        @auth
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                  {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu" role="menu" id='menu2-sub'>
                  <li><a class="dropdown-item" href="{{ route('profile.show') }}">{{ __('Profile') }}</a></li>
                @if (user_is_admin())
                  <li><a class="dropdown-item" href="{{ route('teams.show', Auth::user()->currentTeam->id) }}}">{{ __('Team Settings') }}</a></li>
                @endif
                  <li><a class="dropdown-item" href="{{ route('logout') }}" 
                         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Log out') }}</a>
                    <form method="POST" id="logout-form" action="{{ route('logout') }}">
                        @csrf
                    </form>
                  </li>
                </ul>
        @else
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                  {{ trans('navigation.enter_for_editors') }}
                </a>
                <ul class="dropdown-menu" role="menu" id='menu2-sub'>
            @if (session('status'))
                <div class="alert alert-success mb-3 rounded-0" role="alert">
                    {{ session('status') }}
                </div>
            @endif

                <form method="POST" class="user-enter" action="{{ route('login') }}">
                    @csrf
                    <div style='padding: 5px 0 0 0'>
                        @include('widgets.form.formitem._text', 
                            ['name' => 'email', 
                             'attributes' => ['placeholder' => trans('Email') ]])
                        @include('widgets.form.formitem._password', 
                            ['name' => 'password', 
                             'placeholder' => trans('Password') ])
                    </div>
                    <div>
                        <div class="user-enter-add">
                            <div class="remember_me">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label for="remember_me">{{ __('general.remember_me') }}</label>
                            </div>

                            @if (Route::has('password.request'))
                            <a class="reset_password" href="{{ route('password.request') }}">
                                   {{ __('general.reset_password') }}
                            </a>
                            @endif                        
                        </div>                      
                    </div>
                    <button type="submit" class="btn btn-primary text-uppercase">
                        {{ __('general.log_in') }}
                    </button>
                </form>     
                </ul>
        @endauth
            </li>
        </ul>
    </div>
</nav>

    
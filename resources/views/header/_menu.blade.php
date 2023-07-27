<!--nav class="navbar navbar-expand-lg navbar-light bg-light"-->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
    <!-- Collapsed Hamburger -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">TopKar</a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar  -->
            <ul class="nav navbar-nav">
              <li class="nav-item dropdown" id='menu2'>
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                  {{ trans('navigation.toponyms') }} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu" id='menu1-sub'>
                  <li><a class="dropdown-item" href="{{ route('toponyms.index') }}">{{ trans('navigation.full_list') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('toponyms.with_wrongnames') }}">{{ trans('navigation.with_wrongnames') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('toponyms.with_wd') }}">{{ trans('navigation.with_wd') }}</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown" id='menu1'>
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                  {{ trans('navigation.auxiliaries') }} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu" id='menu1-sub'>
                  <li><a class="dropdown-item" href="{{ route('regions.index') }}">{{ trans('navigation.regions') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('districts.index') }}">{{ trans('navigation.districts') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('settlements.index') }}">{{ trans('navigation.settlements') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('districts1926.index') }}">{{ trans('navigation.districts_1926') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('selsovets1926.index') }}">{{ trans('navigation.selsovets_1926') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('settlements1926.index') }}">{{ trans('navigation.settlements_1926') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('geotypes.index') }}">{{ trans('navigation.geotypes') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('informants.index') }}">{{ trans('navigation.informants') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('recorders.index') }}">{{ trans('navigation.recorders') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('structs.index') }}">{{ trans('navigation.structs') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('sources.index') }}">{{ trans('navigation.sources') }}</a></li>
                </ul>
              </li>
              <li class="nav-item">
                @include('header.lang_switch')
              </li>              
            </ul>
                
            <!-- Right Side Of Navbar -- User and Team-->
            <div class="nav navbar-nav navbar-right">
                @auth
              <li class="nav-item dropdown" id='menu2'>
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                  {{ Auth::user()->name }} <span class="caret"></span>
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
              </li>
                @else

            @if (session('status'))
                <div class="alert alert-success mb-3 rounded-0" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" class="user-enter" action="{{ route('login') }}">
                @csrf
                <div style='padding: 5px 0 0 0'>
                    @include('widgets.form.formitem._text', ['name' => 'email', 
                                                             'attributes' => ['placeholder' => trans('Email') ]])
                    @include('widgets.form.formitem._password', ['name' => 'password', 'placeholder' => trans('Password') ])
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
                @endauth
            </div>
        </div>
    </nav>
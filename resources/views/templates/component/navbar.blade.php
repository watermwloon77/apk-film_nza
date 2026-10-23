<div class="movies_nav">
  <div class="container">
    <nav class="navbar navbar-default">
      <div class="navbar-header navbar-right">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
        <nav>
          <ul class="nav navbar-nav">
            <li class="w3_home_act {{ Route::current()->getName() == 'home' ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
            <li class="dropdown {{ Route::current()->getName() == 'genre' ? 'active' : '' }}">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Genres <b class="caret"></b></a>
              <ul class="dropdown-menu multi-column columns-3">
                <li>
                  <div class="col-sm-4">
                    <ul class="multi-column-dropdown">
                      @foreach ($genres as $index => $genre)
                        <li><a href="{{ route('genre', $genre->id) }}">{{ $genre->name }}</a></li>
                        @if(($index + 1) % 7 == 0 && !$loop->last)
                          </ul>
                        </div>
                        <div class="col-sm-4">
                          <ul class="multi-column-dropdown">
                        @endif
                      @endforeach
                    </ul>
                  </div>
                  <div class="clearfix"></div>
                </li>
              </ul>
            </li>
            <li class="w3_home_act {{ Route::current()->getName() == 'movies' ? 'active' : '' }}"><a href="{{ route('movies') }}">Movies</a></li>
            @auth
              @if(auth()->user()->role?->name === 'admin')
                <li class="{{ request()->routeIs('admin.*') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              @endif
            @endauth

          </ul>
        </nav>
      </div>
    </nav>	
  </div>
</div>
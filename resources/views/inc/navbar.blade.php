      <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
          <div class="container">
              <a class="navbar-brand" href="{{ url('/') }}">
                  {{ config('app.name', 'Laravel Blog') }}
              </a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                  <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <!-- Left Side Of Navbar -->
                  <ul class="navbar-nav mr-auto">
                      <li class="nav-item">
                          <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="/about">About</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="/posts" tabindex="-1" aria-disabled="true">Blog</a>
                      </li>
                  </ul>

                  <!-- Right Side Of Navbar -->
                  <ul class="navbar-nav ml-auto">
                      <li class="nav-item">
                          {!! Form::open(['action' => ['PostsController@index'], 'method' => 'GET', 'class' => 'form-inline my-2 my-lg-0']) !!}
                          <div class="form-group">
                              {{Form::text('search', $search ?? '', ['class' => 'form-control mr-sm-2', 'placeholder' => 'Search'])}}
                          </div>
                          {!! Form::submit('Search', ['class' => 'btn btn-secondary my-2 my-sm-0']) !!}
                          {!! Form::close() !!}
                      </li>
                      <!-- Authentication Links -->
                      @guest
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                      </li>
                      @if (Route::has('register'))
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                      </li>
                      @endif
                      @else
                      <li class="nav-item dropdown">
                          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                              {{ Auth::user()->name }} <span class="caret"></span>
                          </a>

                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                              <a class="dropdown-item" href="/dashboard">
                                  {{ __('Settings') }}
                              </a>
                              <a class="dropdown-item" href="/posts/create">
                                  {{ __('Create Blog Post') }}
                              </a>
                              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                  {{ __('Logout') }}
                              </a>

                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                  @csrf
                              </form>
                          </div>
                      </li>
                      @endguest
                  </ul>
              </div>
          </div>
      </nav>

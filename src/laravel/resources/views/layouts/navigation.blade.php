<div class="row">
    <div class="col-12 justify-center">
        <div class="">
            <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
                <a class="navbar-brand" href="{{ route('dashboard') }}">Dashboard</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav nav-fill w-100">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{route('projects.index')}}">Projects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('texts.index')}}">Texts</a>
                        </li>

                        @if(env('APP_ENV') != 'production')
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('')}}/docs/">API Docs</a>
                        </li>
                        @endif
                        <li>
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <a class="dropdown-item" href="{{route('logout')}}"
                                       onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>



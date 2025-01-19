<div class="row navbar-light bg-light">
    <div class="col-12 justify-center">
        <div class="">
            <nav class="navbar navbar-expand-lg">
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
                        <li>
                            <div class="dropdown">
                                <a class="btn dropdown-toggle" href="#" data-toggle="dropdown">{{ Auth::user()->name }}
                                    <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{route('profile.edit')}}">Edit</a></li>
                                    <div class="dropdown-divider"></div>
                                    <!-- Authentication -->
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <a class="dropdown-item" href="{{route('logout')}}"
                                               onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>



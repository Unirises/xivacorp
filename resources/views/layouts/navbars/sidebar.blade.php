<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            <img src="{{ asset('argon') }}/img/brand/blue.png" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                    <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->name }}</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('argon') }}/img/brand/blue.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            <!-- TODO: Do active navigation stuff -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
                @if(auth()->user()->role->value == 0)
                <li class="nav-item">
                    <a class="nav-link" href="#companies" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="companies">
                        <i class="ni ni-building"></i>
                        <span class="nav-link-text">Companies</span>
                    </a>

                    <div class="collapse hide" id="companies">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('company.index') }}">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('company.create') }}">
                                    Add New
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="#employees" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="employees">
                        <i class="ni ni-badge"></i>
                        <span class="nav-link-text">Employees</span>
                    </a>

                    <div class="collapse hide" id="employees">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employees.index') }}">
                                    Overview
                                </a>
                            </li>
                            @if(auth()->user()->role->value == 0)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employees.create') }}">
                                    Add New
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @if(auth()->user()->role->value == 0)
                <li class="nav-item">
                    <a class="nav-link" href="#types" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="types">
                        <i class="ni ni-settings-gear-65"></i>
                        <span class="nav-link-text">Manage Types</span>
                    </a>

                    <div class="collapse hide" id="types">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('types.index') }}">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('types.create') }}">
                                    Add New
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="#marketplace" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="marketplace">
                        <i class="ni ni-bag-17"></i>
                        <span class="nav-link-text">Marketplace</span>
                    </a>

                    <div class="collapse hide" id="marketplace">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('marketplace.index') }}">
                                    Overview
                                </a>
                            </li>
                            @if(auth()->user()->role->value == 0)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('marketplace.create') }}">
                                    Add New
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#teleconsult" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="teleconsult">
                        <i class="ni ni-circle-08"></i>
                        <span class="nav-link-text">Teleconsult</span>
                    </a>

                    <div class="collapse hide" id="teleconsult">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('consultations.index') }}">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('consultations.create') }}">
                                    Schedule
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#test_services" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="test_services">
                        <i class="ni ni-favourite-28"></i>
                        <span class="nav-link-text">Tests and Services</span>
                    </a>

                    <div class="collapse hide" id="test_services">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('services.index') }}">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('services.create') }}">
                                    Schedule
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if(auth()->user()->role->value == 0 || auth()->user()->role->value == 1)
                <li class="nav-item">
                    <a class="nav-link" href="#forms" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="forms">
                        <i class="ni ni-collection"></i>
                        <span class="nav-link-text">Forms</span>
                    </a>

                    <div class="collapse hide" id="forms">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('forms.index') }}">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('forms.create') }}">
                                    Add New
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
            </ul>
              <!-- Divider -->
              <hr class="my-3">
            <!-- Heading -->
            <h6 class="navbar-heading text-muted">Other Services</h6>
            <!-- Navigation -->
            <ul class="navbar-nav mb-md-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('news') }}">
                        <i class="ni ni-planet text-blue"></i>News and Updates
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://xivahealth.io">
                        <i class="ni ni-cloud-download-95 text-blue"></i>Book Vaccination Service
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

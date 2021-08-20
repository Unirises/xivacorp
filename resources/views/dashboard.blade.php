@extends('layouts.app')

@section('content')
@include('layouts.headers.cards')
<div class="container-fluid mt--7">
    <div class="row mt-5">
        <a href="{{ route('logout') }}" class="btn btn-primary btn-block mb-2" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
            <i class="ni ni-user-run"></i>
            <span>{{ __('Logout') }}</span>
        </a>
        @if($canView)
        @foreach($companies as $company)
        <div class="col{{ $companies->count() > 1 ? '-6' : '' }} mt-2">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{ $company->name }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <span>{{ $company->users->count() }} Registered Users</span> |
                    <span>{{ $company->users()->where('role', 4)->count() }} Employees</span><br><br>
                    @if(auth()->user()->role->value == 0)
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">Service</th>
                                    <th scope="col" class="sort" data-sort="status"># of Serviced Employees</th>
                                </tr>
                            </thead>
                            <br>
                            <tbody class="list">
                                @foreach($company->statistics as $key => $stat)
                                <tr>
                                    <th>{{ $key }}</th>
                                    <td>{{ count($stat) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    @if(auth()->user()->role->value != 0)
                    @foreach($customArray as $day => $data)
                    <b>{{ \Carbon\Carbon::now()->format('M') . ' ' . $day}}</b>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">Service Name</th>
                                    <th scope="col" class="sort" data-sort="status"># of Serviced Employees</th>
                                </tr>
                            </thead>
                            <br>
                            <tbody class="list">
                                @foreach($data as $key => $datum)
                                <tr>
                                    <th>{{ $key }}</th>
                                    <td>{{ count($datum) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    @include('layouts.footers.auth')
</div>
@endsection
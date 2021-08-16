@extends('layouts.app')

@section('content')
@include('layouts.headers.cards')
<div class="container-fluid mt--7">
    <div class="row mt-5">
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
                    <span>{{ $company->users()->where('role', 4)->count() }} Employees</span>
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
                                @foreach($company->statistics as $stat)
                                <tr>
                                    <th>{{ $stat->meta }}</th>
                                    <td>{{ $stat['serviced'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @include('layouts.footers.auth')
</div>
@endsection

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
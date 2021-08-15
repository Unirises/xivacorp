@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Employees @if(auth()->user()->role->value == 0)<a href="{{ route('employees.create') }}"><i class="fas fa-plus-square text-danger ml-1"></i></a>@endif</h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">ID</th>
                                <th scope="col" class="sort" data-sort="budget">Name</th>
                                <th scope="col" class="sort" data-sort="budget">Current Working Hours</th>
                                <th scope="col" class="sort" data-sort="budget">Company</th>
                                <th scope="col" class="sort" data-sort="status">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($hcps as $hcp)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{ $hcp->id }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    {{ $hcp->name }}
                                </td>
                                <td class="budget">
                                    <ul>
                                        @if($hcp->hours)
                                        @foreach(json_decode($hcp->hours) as $day => $hour)
                                        <li>{{ ucwords($day) }} - {{ ucwords($hour[0]) }}</li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </td>
                                <form method="POST" action="{{ route('change-company.change', $hcp->id) }}">
                                    <td class="budget">
                                        <select class="custom-select" id="code" name="code">
                                            <option disabled>Choose...</option>
                                            @foreach($companies as $company)
                                            <option value="{{ $company->code }}" {{ (old('code', $hcp->workspace_id ?? 0) == $company->code ? 'selected' : '') }}>{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        @if(auth()->user()->role->value == 0)
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value="Update">
                                        </div>
                                        @endif
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection

@push('js')
@endpush
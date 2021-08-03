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
                    <h3 class="mb-0">Companies @if(auth()->user()->role->value == 0)<a href="{{ route('company.create') }}"><i class="fas fa-plus-square text-danger ml-1"></i></a>@endif</h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Company Name</th>
                                <th scope="col" class="sort" data-sort="budget">Code</th>
                                <th scope="col" class="sort" data-sort="status">Employer</th>
                                <th scope="col">Contact Phone</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($companies as $company)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{ $company->name }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    {{ $company->code }}
                                </td>
                                <td>
                                    {{ $company->employer }}
                                </td>
                                <td>
                                    {{ $company->contact }}
                                </td>
                                <td>
                                    <a href="{{ route('company.edit', $company) }}" class="btn btn-primary my-4">Update</button>
                                </td>
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
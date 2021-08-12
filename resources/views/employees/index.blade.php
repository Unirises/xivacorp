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
                                <th scope="col" class="sort" data-sort="name">Workspace ID</th>
                                <th scope="col" class="sort" data-sort="budget">Name</th>
                                <th scope="col" class="sort" data-sort="status">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($employees as $employee)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{ $employee->workspace_id }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    {{ $employee->name }}
                                </td>
                                <td>
                                    {{ $employee->email }}
                                </td>
                                <td>
                                    @if($employee->hcp_data)
                                    {{ $employee->hcp_data->type->name }} - {{ $employee->hcp_data->prc_id }}
                                    @else
                                    {{ strtoupper($employee->role->description)  }}
                                    @endif
                                </td>
                                <td>
                                    @if($employee->id !== auth()->user()->id && auth()->user()->role->value == 0)
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary my-4">Update</a>
                                    <form method="POST" action="{{ route('employees.destroy', $employee->id) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-danger" value="Delete user">
                                        </div>
                                    </form>
                                    @if($employee->role->value == 1 && $employee->hours != null)
                                    <form method="POST" action="{{ route('employees.reset-hours', $employee->id) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-warning" value="Reset Working Hours">
                                        </div>
                                    </form>
                                    @endif
                                    @endif
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
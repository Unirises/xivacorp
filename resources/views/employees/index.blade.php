@extends('layouts.app')
@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
@endsection

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Active Personnel List  @if(auth()->user()->role->value == 0)<a href="{{ route('employees.create') }}"><i class="fas fa-plus-square text-danger ml-1"></i></a>@endif</h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="employee_table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Workspace ID</th>
                                <th scope="col" class="sort" data-sort="budget">Name</th>
                                @if(auth()->user()->role->value == 0)
                                <th scope="col" class="sort" data-sort="status">Email</th>
                                @endif
                                <th scope="col">Role</th>
                                <th scope="col">Recently Serviced</th>
                                @if(auth()->user()->role->value == 0)
                                <th scope="col">Contact Details</th>
                                <th scope="col">Actions</th>
                                @endif
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
                                @if(auth()->user()->role->value == 0)
                                <td>
                                    {{ $employee->email }}
                                </td>
                                @endif
                                <td>
                                    @if($employee->hcp_data)
                                    {{ $employee->hcp_data->type->name }} - {{ $employee->hcp_data->prc_id }} <br>
                                    <img src="{{ $employee->hcp_data->photo }}" alt="HCP Photo" srcset="" style="max-width: 100%">
                                    @else
                                    {{ strtoupper($employee->role->description)  }}
                                    @endif
                                </td>
                                <td>
                                    {{ $employee->recent_service }}
                                </td>
                                @if(auth()->user()->role->value == 0)
                                <td>
                                    Mobile Number: {{ $employee->mobile_number ?? 'N/A' }} | Telephone Number: {{ $employee->telephone_number ?? 'N/A' }}
                                </td>
                                <td>
                                    @if($employee->id !== auth()->user()->id && auth()->user()->role->value == 0)
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary my-4">Update</a>
                                    @if($employee->id != 1)
                                    <form method="POST" action="{{ route('employees.destroy', $employee->id) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-danger" value="Delete user">
                                        </div>
                                    </form>
                                    @endif
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
                                @endif
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
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#employee_table thead tr').clone(true).appendTo('#employee_table thead');
        $('#employee_table thead tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        var table = $('#employee_table').DataTable({
            orderCellsTop: true,
            fixedHeader: true
        });
    });
</script>
@endpush
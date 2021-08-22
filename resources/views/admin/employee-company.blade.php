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
                    <h3 class="mb-0">Personnel List – Change Company & Roles</h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="employee_table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">User</th>
                                <th scope="col" class="sort" data-sort="budget">Previous Workspace ID</th>
                                <th scope="col" class="sort" data-sort="budget">New Workspace ID</th>
                                <th scope="col" class="sort" data-sort="budget">Previous Role</th>
                                <th scope="col" class="sort" data-sort="budget">Current Role</th>
                                <th scope="col" class="sort" data-sort="budget">Company ID</th>
                                <th scope="col" class="sort" data-sort="budget">Contact Details</th>
                                <th scope="col" class="sort" data-sort="status">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($data as $datum)
                            <tr>

                                <th>
                                    {{ $datum->user->name }}
                                </th>
                                <td>{{ $datum->user->workspace_id }}</td>
                                <td>{{ $datum->workspace_id }}</td>
                                <td>{{ $datum->user->role->description }}</td>
                                <td>
                                    @if($datum->role->value == 1)
                                    {{ $datum->user->hcp_data->type->name }} - {{ $datum->user->hcp_data->prc_id }}
                                    @else
                                    {{ $datum->role->description }}
                                    @endif
                                </td>
                                <td>
                                    <img src="{{ $datum->company_id }}" alt="" style="max-width: 250px; height: auto;">
                                </td>
                                <td>
                                    Mobile Number: {{ $datum->user->mobile_number }} | Telephone Number: {{ $datum->user->telephone_number ?? 'N/A' }}
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('company-notification.approve', $datum->id) }}">
                                        @if(auth()->user()->role->value == 0)
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value="Update">
                                        </div>
                                        @endif
                                    </form>
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
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
                    <h3 class="mb-0">Validated Forms</h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="employee_table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Report Generated</th>
                                <th scope="col" class="sort" data-sort="budget">Form</th>
                                <th scope="col" class="sort" data-sort="budget">Health Care Provider</th>
                                <th scope="col" class="sort" data-sort="budget">Client</th>
                                <th scope="col" class="sort" data-sort="status">Service</th>
                                <th scope="col" class="sort" data-sort="status">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($forms as $form)
                            <tr>
                                <th>{{ \Carbon\Carbon::parse($form->updated_at)->format('m/d/Y g:i A') }}</th>
                                <td>{{ $form->form->name }}</td>
                                <td>{{ $form->answerer->name }}</td>
                                <td>{{ $form->service->client->name }}</td>
                                <td>{{ $form->service->service->meta }}</td>
                                <td>
                                <a href="{{ route('services.forms.export', [$form->service->id, $form->id]) }}" class="btn btn-primary">Export</a>
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
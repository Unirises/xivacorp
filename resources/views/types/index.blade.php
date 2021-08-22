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
                    <h3 class="mb-0">Types @if(auth()->user()->role->value == 0)<a href="{{ route('types.create') }}"><i class="fas fa-plus-square text-danger ml-1"></i></a>@endif</h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="form_table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Classification</th>
                                <th scope="col" class="sort" data-sort="budget">Name</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($types as $type)
                            <tr>
                                <td class="budget">
                                    {{ $type->type->description }}
                                </td>
                                <td>
                                    {{ $type->name }}
                                </td>
                                <td>
                                    <a href="{{ route('types.edit', $type) }}" class="btn btn-primary my-4">Update</button>
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

@push('js')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#form_table thead tr').clone(true).appendTo('#form_table thead');
        $('#form_table thead tr:eq(1) th').each(function(i) {
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
        var table = $('#form_table').DataTable({
            orderCellsTop: true,
            fixedHeader: true
        });
    });
</script>
@endpush
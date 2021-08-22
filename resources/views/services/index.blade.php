@extends('layouts.app')
@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
@endsection
@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Your Pending Bookings</h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tab1">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">Workspace ID</th>
                                    <th scope="col" class="sort" data-sort="name">Client</th>
                                    <th scope="col" class="sort" data-sort="name">Service</th>
                                    <th scope="col" class="sort" data-sort="name">Schedule</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($bookings->where('pending', 1) as $booking)
                                <tr>
                                    <th>
                                        {{ $booking->workspace_id }}
                                    </th>
                                    <td>
                                        {{ $booking->client->name }}
                                    </td>
                                    <td>
                                        {{ $booking->service->meta }}
                                    </td>
                                    <td>
                                        {{ $booking->schedule->format('m/d/Y g:i A') }}
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('services.destroy', $booking->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <div class="form-group">
                                                <input type="submit" class="btn btn-danger btn-block" value="Delete">
                                            </div>
                                        </form>
                                        @if(auth()->user()->role->value == 1)
                                        <form method="POST" action="{{ route('services.accept-booking', $booking->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('PUT') }}

                                            <div class="form-group">
                                                <input type="submit" class="btn btn-success btn-block" value="Accept">
                                            </div>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Recurring Services</h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tab2">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">Workspace ID</th>
                                    <th scope="col" class="sort" data-sort="name">Client</th>
                                    <th scope="col" class="sort" data-sort="name">Service</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($bookings->where('pending', 0)->whereNull('schedule') as $booking)
                                <tr>
                                    <th>
                                        {{ $booking->workspace_id }}
                                    </th>
                                    <td>
                                        {{ $booking->client->name }}
                                    </td>
                                    <td>
                                        {{ $booking->service->meta }}
                                    </td>
                                    <td>
                                        <a href="{{ route('services.show', $booking->id) }}" class="btn btn-block btn-primary">View Records</a>
                                        <a href="{{ route('services.diary.index', $booking->id) }}" class="btn btn-block btn-info">Health Diary</a>
                                        @if(auth()->user()->role->value == 1 && auth()->user()->hcp_data->type_id == 3)
                                        <a href="{{ route('services.prescriptions.create', $booking->id) }}" class="btn btn-block btn-primary">Create New Prescription</a>
                                        @endif
                                        @if($booking->latest_prescription_id)
                                        <a href="{{ route('services.prescriptions.show', [$booking->id, $booking->latest_prescription_id]) }}" class="btn btn-block btn-info">View Latest Prescription</a>
                                        @endif
                                        <br>
                                        <form method="POST" action="{{ route('services.destroy', $booking->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <div class="form-group">
                                                <input type="submit" class="btn btn-danger btn-block" value="Delete">
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Your Upcoming Bookings</h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tab3">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">Workspace ID</th>
                                    <th scope="col" class="sort" data-sort="name">Client</th>
                                    <th scope="col" class="sort" data-sort="name">Service</th>
                                    <th scope="col" class="sort" data-sort="name">Schedule</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($bookings->where('pending', 0)->whereNotNull('hcp_id')->where('schedule', '>', \Carbon\Carbon::now()) as $booking)
                                <tr>
                                    <th>
                                        {{ $booking->workspace_id }}
                                    </th>
                                    <td>
                                        {{ $booking->client->name }}
                                    </td>
                                    <td>
                                        {{ $booking->service->meta }}
                                    </td>
                                    <td>
                                        {{ $booking->schedule->format('m/d/Y g:i A') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('services.show', $booking->id) }}" class="btn btn-block btn-primary">View Records</a>
                                        <a href="{{ route('services.diary.index', $booking->id) }}" class="btn btn-block btn-info">Health Diary</a>
                                        @if(auth()->user()->role->value == 1 && auth()->user()->hcp_data->type_id == 3)
                                        <a href="{{ route('services.prescriptions.create', $booking->id) }}" class="btn btn-block btn-primary">Create New Prescription</a>
                                        @endif
                                        @if($booking->latest_prescription_id)
                                        <a href="{{ route('services.prescriptions.show', [$booking->id, $booking->latest_prescription_id]) }}" class="btn btn-block btn-info">View Latest Prescription</a>
                                        @endif
                                        <br>
                                        <form method="POST" action="{{ route('services.destroy', $booking->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <div class="form-group">
                                                <input type="submit" class="btn btn-danger btn-block" value="Delete">
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Your Completed Bookings</h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tab4">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">Workspace ID</th>
                                    <th scope="col" class="sort" data-sort="name">Client</th>
                                    <th scope="col" class="sort" data-sort="name">Service</th>
                                    <th scope="col" class="sort" data-sort="name">Schedule</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($bookings->where('pending', 0)->whereNotNull('hcp_id')->where('schedule', '<', \Carbon\Carbon::now()) as $booking)
                                <tr>
                                    <th>
                                        {{ $booking->workspace_id }}
                                    </th>
                                    <td>
                                        {{ $booking->client->name }}
                                    </td>
                                    <td>
                                        {{ $booking->service->meta }}
                                    </td>
                                    <td>
                                        {{ $booking->schedule->format('m/d/Y g:i A') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('services.show', $booking->id) }}" class="btn btn-block btn-primary">View Records</a>
                                        <a href="{{ route('services.diary.index', $booking->id) }}" class="btn btn-block btn-info">Health Diary</a>
                                        @if(auth()->user()->role->value == 1 && auth()->user()->hcp_data->type_id == 3)
                                        <a href="{{ route('services.prescriptions.create', $booking->id) }}" class="btn btn-block btn-primary">Create New Prescription</a>
                                        @endif
                                        @if($booking->latest_prescription_id)
                                        <a href="{{ route('services.prescriptions.show', [$booking->id, $booking->latest_prescription_id]) }}" class="btn btn-block btn-info">View Latest Prescription</a>
                                        @endif

                                        @if(auth()->user()->role->value == 0)
                                        <br>
                                        <form method="POST" action="{{ route('services.destroy', $booking->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <div class="form-group">
                                                <input type="submit" class="btn btn-danger btn-block" value="Delete">
                                            </div>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Card footer -->
                @if(auth()->user()->role->value == 0)
                <div class="card-footer py-4">
                    <a href="{{ route('services.export') }}" class="btn btn-primary">Export All Bookings</a>
                </div>
                @endif
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
        $('#tab1 thead tr').clone(true).appendTo('#tab1 thead');
        $('#tab1 thead tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            $('input', this).on('keyup change', function() {
                if (tab1.column(i).search() !== this.value) {
                    tab1
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        var tab1 = $('#tab1').DataTable({
            orderCellsTop: true,
            fixedHeader: true
        });
        $('#tab2 thead tr').clone(true).appendTo('#tab2 thead');
        $('#tab2 thead tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            $('input', this).on('keyup change', function() {
                if (tab2.column(i).search() !== this.value) {
                    tab2
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        var tab2 = $('#tab2').DataTable({
            orderCellsTop: true,
            fixedHeader: true
        });
        $('#tab3 thead tr').clone(true).appendTo('#tab3 thead');
        $('#tab3 thead tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            $('input', this).on('keyup change', function() {
                if (tab3.column(i).search() !== this.value) {
                    tab3
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        var tab3 = $('#tab3').DataTable({
            orderCellsTop: true,
            fixedHeader: true
        });
        $('#tab4 thead tr').clone(true).appendTo('#tab4 thead');
        $('#tab4 thead tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            $('input', this).on('keyup change', function() {
                if (tab4.column(i).search() !== this.value) {
                    tab4
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        var tab4 = $('#tab4').DataTable({
            orderCellsTop: true,
            fixedHeader: true
        });
    });
</script>
@endpush
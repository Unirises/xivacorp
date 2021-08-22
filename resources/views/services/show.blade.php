@extends('layouts.app')
@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
@endsection
@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    @if(auth()->user()->role->value == 1)
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Create New Answerable Form</h3>
                </div>
                <!-- Light table -->
                <form action="{{ route('services.forms.create', $current_id) }}" method="POST">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <select class="custom-select" id="form_id" name="form_id">
                                    <option disabled>Choose...</option>
                                    @foreach($forms as $form)
                                    <option value="{{ $form->id }}">{{ $form->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <select class="custom-select" id="user_id" name="user_id">
                                    <option disabled>Choose...</option>
                                    <option value="{{ $service->client->id }}">{{ $service->client->name }}</option>
                                    <option value="{{ auth()->user()->id }}">Health Care Provider (You)</option>
                                </select>
                                <div id="exportDiv">
                                    <input type="checkbox" name="checkbox" id="checkbox" value=""> <label for="checkbox">Make this form exportable</label>
                                </div>
                                <div id="signatureDiv">
                                    <input type="checkbox" name="client_signature" id="client_signature" value=""> <label for="client_signature">Require client signature.</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Forms</h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="form_table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">Form</th>
                                    <th scope="col" class="sort" data-sort="name">Answerable By</th>
                                    <th scope="col" class="sort" data-sort="name">Timestamp</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($available_forms as $form)
                                <tr>
                                    <th>{{ $form->form->name }}</th>
                                    <td>{{ $form->answerer->name }}</td>
                                    <td>{{ $form->created_at }}</td>
                                    <td>
                                        @if( $form->answerable_by == auth()->user()->id)
                                        <a href="{{ route('services.forms.answer', [$service->id, $form->id]) }}" class="btn btn-primary my-4">Answer/Update</a>
                                        @endif
                                        @if($form->answer ?? null)
                                        <a href="{{ route('services.forms.response', [$service->id, $form->id]) }}" class="btn btn-primary my-4">View Response</a>
                                        @endif
                                        @if(auth()->user()->role->value != 4)
                                        <form method="POST" action="{{ route('services.forms.delete', [$service->id, $form->id]) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <div class="form-group">
                                                <input type="submit" class="btn btn-danger" value="Delete Form">
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
    jQuery(document).ready(function($) {
        $("#exportDiv").hide();
        $("#signatureDiv").show();
        
        $("#user_id").change(function() {
            if($(this).children('option:selected').index() == 1) {
                $("#exportDiv").hide();
                $("#signatureDiv").show();
            } else {
                $("#exportDiv").show();
                $("#signatureDiv").hide();
            }
        });
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
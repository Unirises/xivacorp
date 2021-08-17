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
                    <h3 class="mb-0">Scan your QR Code</h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <h1 class="text-center">Name:</h1>
                    <b class="text-center">
                        <h1 id="client_name">{{ $form->service->client->name }}</h1>
                    </b>
                    <h1 class="text-center">Report #:</h1>
                    <b class="text-center">
                        <h1 id="report_id">{{ $form->service->workspace_id . "-" . $form->service_id . $form->form_id . $form->answerable_by . $form->id }}</h1>
                    </b>
                    <h1 class="text-center">Date Performed:</h1>
                    <b class="text-center">
                        <h1 id="date_performed">{{ \Carbon\Carbon::parse($form->updated_at)->toDayDateTimeString() }}</h1>
                    </b>
                    <h1 class="text-center">Findings:</h1>
                    <b>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" class="sort" data-sort="name">Label</th>
                                        <th scope="col" class="sort" data-sort="status">Value</th>
                                    </tr>
                                </thead>
                                <br>
                                <tbody class="list">
                                    @if($form->answer)
                                    @foreach($form->answer as $answerDetail)
                                    @if($answerDetail->label != "Hidden Field")
                                    <tr>
                                        <!-- <th scope="row">
                                        {{ $answerDetail->name }}
                                    </th> -->
                                        <td>{{ str_replace("&nbsp;", "", strip_tags($answerDetail->label)) }}</td>
                                        <td>{{ $answerDetail->value }}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </b>
                </div>
                <div class="card-footer">
                    <a href="{{ route('services.forms.export', [$form->service->id, $form->id]) }}" class="btn btn-primary">Download Report</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
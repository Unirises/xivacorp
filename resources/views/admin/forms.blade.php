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
                    <h3 class="mb-0">Validated Forms</h3>
                </div>
                <!-- Light table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Service ID</th>
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
                                <th>{{ $form->service_id }}</th>
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
@endpush
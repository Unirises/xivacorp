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
                    <h3 class="mb-0">
                        @if($consultation->service_id != null)
                        {{ $consultation->service->type->description }} | {{ $consultation->service->name }}
                        @else
                        Teleconsult Booking
                        @endif
                        @if((auth()->user()->role->value == 0 || auth()->user()->role->value == 1) && Route::is('services.show'))
                        @if($consultation->prescription)
                        <a href="{{ route('services.prescriptions.edit', [$consultation->id, $consultation->prescription->id]) }}"><i class="fas fa-plus-square text-danger ml-1"></i> Edit Prescription</a>
                        <a href="{{ route('services.prescriptions.show', [$consultation->id, $consultation->prescription->id]) }}"><i class="fas fa-image text-danger ml-1"></i> Export as Photo</a>
                        @else
                        <a href="{{ route('services.prescriptions.create', $consultation->id) }}"><i class="fas fa-plus-square text-danger ml-1"></i> Create Prescription</a>
                        @endif
                        @endif
                        @if(Route::is('services.show'))
                        <a href="{{ route('services.diary.index', $consultation->id) }}"><i class="fas fa-plus-square text-danger ml-1"></i> Health Diary</a>
                        @endif
                    </h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@if($forms ?? '')
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">
                        Forms
                    </h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Form Name</th>
                                <th scope="col" class="sort" data-sort="name">Required</th>
                                <th scope="col" class="sort" data-sort="name">Answerable By</th>
                                <th scope="col" class="sort" data-sort="name">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @if($consultation->forms)
                            @foreach($consultation->forms as $form)
                            <tr>
                                <th scope="row">
                                    {{ $form->name }}
                                </th>
                                <td>
                                    {{ $form->pivot->required == true ? 'Yes' : 'No' }}
                                </td>
                                <td>
                                    {{ $form->pivot->answerable_by == $consultation->user->id ? $consultation->user->name : $consultation->provider->name }}
                                </td>
                                <td>
                                    <a href="{{ Route::is('consultations.index') ? route('consultations.show', $consultation) : route('services.show', $consultation) }}" class="btn btn-primary my-4">Answer/Update</a>
                                    <a href="{{ Route::is('consultations.index') ? route('consultations.show', $consultation) : route('services.show', $consultation) }}" class="btn btn-primary my-4">View Response</a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                    @if((auth()->user()->role->value == 0 || auth()->user()->role->value == 1) && Route::is('services.show'))
                    <form action="{{ route('update-form', $consultation->id) }}" method="POST">
                        @csrf
                        <h1>Answerable by HCP</h1>
                        @foreach($forms as $form)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $form->id }}" name="hcp-forms[]">
                            <label class="form-check-label" for="hcp-form-{{ $form->id }}">
                                {{ $form->name }}
                            </label>
                        </div>
                        @endforeach

                        <h1 class="mt-2">Answerable by Client</h1>
                        @foreach($forms as $form)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $form->id }}" name="user-forms[]">
                            <label class="form-check-label" for="user-form-{{ $form->id }}">
                                {{ $form->name }}
                            </label>
                        </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary mt-3">Update Record</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endif
@endsection

@if($consultation->room_id)
@push('js')
<script src="https://unpkg.com/@daily-co/daily-js"></script>
<script>
    callFrame = window.DailyIframe.createFrame({
        showLeaveButton: true,
        showFullscreenButton: true,
    });
    callFrame.join({
        url: '{{ $consultation->room_id }}',

    });
</script>
@endpush
@endif
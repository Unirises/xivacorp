@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row mb-5">
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
                    @if($consultation->room_id)
                    @if($consultation->is_ongoing)
                    <span>If the embedded video call is not working, <a href="{{ $consultation->room_id }}">try this link instead.</a></span>
                    @else
                    <span>Your teleconsultation room is only available between {{ $consultation->starts_at->format('m/d/Y g:i A') }} - {{ $consultation->ends_at->format('g:i A') }}.</span>
                    @endif
                    @endif
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                    @if(auth()->user()->role->value == 1)
                    <a href="{{ route('consultations.prescriptions.create', $consultation->id) }}" class="btn btn-block btn-primary">Create New Prescription</a>
                    @endif
                    @if($consultation->latest_prescription_id)
                    <a href="{{ route('consultations.prescriptions.show', [$consultation->id, $consultation->latest_prescription_id]) }}" class="btn btn-block btn-info">View Latest Prescription</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if($forms ?? '')
    <div class="row mb-3">
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
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">Form Name</th>
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
                                        {{ $form->pivot->answerable_by == $consultation->user->id ? $consultation->user->name : $consultation->provider->name }}
                                    </td>
                                    <td>
                                        @if( $form->pivot->answerable_by == auth()->user()->id)
                                        <a href="{{ Route::is('consultations.show') ? route('consultations.forms.edit', [$consultation, $form->pivot->form_id, $form->pivot->answerable_by]) : route('services.forms.edit', [$consultation, $form->pivot->form_id, $form->pivot->answerable_by]) }}" class="btn btn-primary my-4">Answer/Update</a>
                                        @endif
                                        <a href="{{ Route::is('consultations.show') ? route('consultations.forms.show', [$consultation, $form->pivot->form_id, $form->pivot->answerable_by]) : route('services.forms.show', [$consultation, $form->pivot->form_id, $form->pivot->answerable_by]) }}" class="btn btn-primary my-4">View Response</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                    @if((auth()->user()->role->value == 0 || auth()->user()->role->value == 1))
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
    @endif
    <div class="mb-5">
        <br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection

@if($consultation->room_id && $consultation->is_ongoing)
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
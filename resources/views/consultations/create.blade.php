@extends('layouts.app')

@section('head')
@livewireStyles
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <form action="{{ Route::is('consultations.create') ? route('consultations.store') : route('services.store') }}" method="POST">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">Schedule a
                            @if(Route::is('consultations.create'))
                            Teleconsultation
                            @else
                            Health Services Booking
                            @endif
                        </h3>
                    </div>
                    <!-- Light table -->
                    @csrf
                    <div class="card-body px-lg-5 py-lg-5">
                    @if(Route::is('services.create'))
                        <div class="input-group input-group-alternative mb-3">
                            <div class="input-group-prepend">   
                                <label class="input-group-text" for="service_id">Select a service</label>
                            </div>
                            <select class="custom-select" id="service_id" name="service_id">
                                <option disabled>Choose...</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ (old('service_id', $service->id ?? 0) == $service->id ? 'selected' : '') }}>{{ $service->type->description }} — {{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if(auth()->user()->role->value != 4)
                        <div class="input-group input-group-alternative mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="user_id">Select a user</label>
                            </div>
                            <select class="custom-select" id="user_id" name="user_id">
                                <option disabled>Choose...</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (old('user_id', $user->id ?? 0) == $user->id ? 'selected' : '') }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @livewire('schedule')
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush
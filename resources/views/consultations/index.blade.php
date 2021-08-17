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
                    <h3 class="mb-0">Your 
                    @if(Route::is('consultations.index'))
                    Teleconsultations
                    @else
                    Health Services Bookings
                    @endif

                    @if(auth()->user()->role->value == 0)
                    @if(Route::is('consultations.index'))
                    <a href="{{ route('consultations.export') }}"><i class="fas fa-plus-square text-danger ml-1"></i> Export Records</a>
                    @else
                    <a href="{{ route('services.export') }}"><i class="fas fa-plus-square text-danger ml-1"></i> Export Records</a>
                    @endif
                    </h3>
                    @endif
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">Status</th>
                                    <th scope="col" class="sort" data-sort="name">Provider</th>
                                    <th scope="col" class="sort" data-sort="name">Client</th>
                                    @if(Route::is('services.index'))
                                    <th scope="col" class="sort" data-sort="name">Service Provided</th>
                                    @endif
                                    <th scope="col" class="sort" data-sort="name">Schedule</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach($consultations as $consultation)
                                <tr>
                                    <th scope="row">
                                        <p class="text-{{ $consultation->status_color }}">
                                            <b>{{ $consultation->status->description }}</b>
                                        </p>
                                    </th>
                                    <td>
                                        {{ $consultation->provider->name }}
                                    </td>
                                    <td>
                                        {{ $consultation->user->name }}
                                    </td>
                                    @if(Route::is('services.index'))
                                    <td>
                                        {{ $consultation->service->type->description ?? '' }} — {{ $consultation->service->name ?? '' }}
                                    </td>
                                    @endif
                                    <td>
                                        {{ $consultation->starts_at->format('m/d/Y g:i A') }} - {{ $consultation->ends_at->format('g:i A') }}
                                    </td>
                                    <td>
                                        <a href="{{ Route::is('consultations.index') ? route('consultations.show', $consultation) : route('services.show', $consultation) }}" class="btn btn-primary my-4">
                                            @if($consultation->status->value == 1)
                                            Attend
                                            @else
                                            View
                                            @endif
                                        </a>
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
    @include('layouts.footers.auth')
</div>
@endsection

@push('js')
@endpush
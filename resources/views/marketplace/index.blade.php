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
                    <h3 class="mb-0">Marketplace Listing @if(auth()->user()->role->value == 0)<a href="{{ route('marketplace.create') }}"><i class="fas fa-plus-square text-danger ml-1"></i></a>@endif</h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <div class="row icon-examples" style="column-gap: 1rem; row-gap: 1rem;">
                        @foreach($items as $item)
                        <div class="card col-lg-3 col-md-5">
                            @if($item->photo)
                            <img class="card-img-top" src="{{ $item->photo }}" alt="Card image cap">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }} - Php {{ $item->price }}</h5>
                                <p class="card-text">{{ $item->description }}</p>
                                @if(auth()->user()->role->value == 0)
                                <a href="{{ route('marketplace.edit', $item->id) }}" class="btn btn-primary">Update</a>
                                @else
                                <a href="javascript:void(Tawk_API.toggle())" class="btn btn-primary">Message to Inquire</a>
                                @endif
                            </div>
                        </div>
                        @endforeach
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
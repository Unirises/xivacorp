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
                        {{ $consultation->service_type->description }}
                    </h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    @foreach($consultation->forms as $form)
                    <div class="card col-lg-3 col-md-5">
                        {{ $form }}
                    </div>
                    @endforeach

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
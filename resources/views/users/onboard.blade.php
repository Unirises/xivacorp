@extends('layouts.app', ['class' => 'bg-default'])

@section('head')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
@endsection

@section('content')
@include('layouts.headers.cards')
<div class="container mt--8 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card bg-secondary shadow border-0">
                <form role="form" method="POST" action="{{ route('register-onboard') }}" enctype="multipart/form-data">
                    @csrf
                    @include('employees.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script>
    window.addEventListener('load', function() {
        var dropdown = document.getElementById('type');
        var hcpToggleDiv = document.getElementById('forHcp');
        if (dropdown.value === '1') {
            hcpToggleDiv.style.display = "block";
        } else {
            hcpToggleDiv.style.display = "none";
        }
        dropdown.addEventListener('change', (event) => {
            if (dropdown.value === '1') {
                hcpToggleDiv.style.display = "block";
            } else {
                hcpToggleDiv.style.display = "none";
            }
        })

        $('input.timepicker').timepicker({});
    })
</script>
@endpush
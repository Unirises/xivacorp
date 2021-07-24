@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
@include('layouts.headers.guest')

<div class="container mt--8 pb-5">
    <!-- Table -->
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card bg-secondary shadow border-0">
                <form role="form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    @include('employees.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
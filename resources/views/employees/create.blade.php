@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">Create a New Employee</h3>
                    </div>
                    <!-- Light table -->
                    @csrf
                    <div class="card-body px-lg-5 py-lg-5">
                        @include('employees.form')
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">Create Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection

@push('js')
@endpush
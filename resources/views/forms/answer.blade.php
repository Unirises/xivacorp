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
                    <h3 class="mb-0">{{ $answer->form->name }}</h3>
                </div>
                <!-- Light table -->
                <div class="card-body px-lg-5 py-lg-5">
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Field Name</th>
                                    <th scope="col">Label/Description</th>
                                    <th scope="col">Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($answer->data as $answerDetail)
                                <tr>
                                    <th scope="row">
                                        {{ $answerDetail->name }}
                                    </th>
                                    <td>{{ $answerDetail->label }}</td>
                                    <td>{{ $answerDetail->value }}</td>
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
@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">{{ $form->name }}</h3>
                </div>
                <!-- Light table -->
                <div class="card-body px-lg-5 py-lg-5">
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <!-- <th scope="col">Field Name</th> -->
                                    <th scope="col">Label/Description</th>
                                    <th scope="col">Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($form->answer)
                                @foreach($form->answer as $answerDetail)
                                @if($answerDetail->label != "Hidden Field")
                                <tr>
                                    <!-- <th scope="row">
                                        {{ $answerDetail->name }}
                                    </th> -->
                                    <td>{{ str_replace("&nbsp;", "", strip_tags($answerDetail->label)) }}</td>
                                    <td>{{ $answerDetail->value }}</td>
                                </tr>
                                @endif
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($form->answerer->role->value == 1 && $form->is_exportable)
                    <!-- Card footer -->
                    <div class="card-footer py-4">
                        <a href="{{ route('services.forms.export', [$serviceId, $form->id]) }}" class="btn btn-primary">Export</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if($form->signature ?? null)
    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="mb-0">Client Signature</h3>
                </div>
                <div class="card-body px-lg-5 py-lg-5">
                <img src="{{ $form->signature }}" style="max-width: 100%" alt="Client Signature" />
                </div>
            </div>
        </div>
    </div>
    @endif
    @if($form->photo ?? null)
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="mb-0">Photo</h3>
                </div>
                <div class="card-body px-lg-5 py-lg-5">
                <img src="{{ $form->photo }}" style="max-width: 100%" alt="Result photo" />
                </div>
            </div>
        </div>
    </div>
    @endif
    @include('layouts.footers.auth')
</div>
@endsection
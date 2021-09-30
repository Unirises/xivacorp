@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <form action="{{ route('forms.store') }}" method="POST">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">Create a new fillable form</h3>
                    </div>
                    <!-- Light table -->
                    <div class="card-body px-lg-5 py-lg-5">
                        @csrf
                        <input name="data" type="hidden" value="" id="data">
                        <div class="form-group{{ $errors->has('form_name') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('form_name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" type="text" name="form_name" value="{{ old('form_name') ?? $form->name ?? '' }}" required autofocus>
                            </div>
                            @if ($errors->has('name'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                        <!-- <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="required" name="required">
                            <label class="form-check-label" for="required">This form is required before booking any future tests, vaccinations, and other services.</label>
                        </div> -->
                        <div id="build-wrap"></div>
                        @if ($errors->has('data'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('data') }}</strong>
                        </span>
                        @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
<script>
    jQuery(function($) {
        var fbTemplate = document.getElementById("build-wrap");
        var options = {
            onSave: function(evt, formData) {
                var jsonData = formBuilder.actions.getData('json', true);
                document.getElementById("data").value = jsonData;
            },
            disableFields: ['autocomplete', 'button', 'date', 'file', 'hidden', 'starRating', 'textarea']
        };
        var formBuilder = $(fbTemplate).formBuilder(options);
    });
</script>
@endpush
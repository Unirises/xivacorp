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
                    <div class="card-header bg-transparent ">
                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }} mb-3">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-folder-17"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Workspace Identifier" type="text" name="code" value="{{ old('code') }}" required autofocus>
                            </div>
                            @if ($errors->has('code'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('code') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="type">I am signing up as</label>
                                </div>
                                <select class="custom-select" id="type" name="type">
                                    <option disabled>Choose...</option>
                                    <option value="1">Health Care Provider</option>
                                    <option value="2">HR</option>
                                    <option value="3">Company / In-house Clinic</option>
                                    <option value="4">Employee</option>
                                </select>
                            </div>
                            @if ($errors->has('type'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div id="forHcp">
                            <div class="form-group{{ $errors->has('role') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="role">I am a</label>
                                    </div>
                                    <select class="custom-select" id="role" name="role">
                                        <option disabled>Choose...</option>
                                        @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('role'))
                                <span class="invalid-feedback" style="display: block;" role="alert">
                                    <strong>{{ $errors->first('role') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('prc_id') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">PRC ID</span>
                                    </div>
                                    <input class="form-control{{ $errors->has('prc_id') ? ' is-invalid' : '' }}" placeholder="" type="text" name="prc_id" value="{{ old('prc_id') }}" required autofocus>
                                </div>
                                @if ($errors->has('prc_id'))
                                <span class="invalid-feedback" style="display: block;" role="alert">
                                    <strong>{{ $errors->first('prc_id') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('selfie') ? ' has-danger' : '' }}">
                                <div class="custom-file">
                                    <input type="file" name="selfie" class="custom-file-input" id="selfie" required>
                                    <label class="custom-file-label" for="selfie">Attach your selfie with your PRC ID.</label>
                                </div>

                                @if ($errors->has('selfie'))
                                <span class="invalid-feedback" style="display: block;" role="alert">
                                    <strong>{{ $errors->first('selfie') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-lg-5">
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" type="text" name="name" value="{{ old('name') }}" required autofocus>
                            </div>
                            @if ($errors->has('name'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" type="password" name="password" required>
                            </div>
                            @if ($errors->has('password'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __('Confirm Password') }}" type="password" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-12">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" id="customCheckRegister" type="checkbox">
                                    <label class="custom-control-label" for="customCheckRegister">
                                        <span class="text-muted">{{ __('I agree with the') }} <a href="#!">{{ __('Privacy Policy') }}</a></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-4">{{ __('Create account') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    window.addEventListener('load', function() {
        var dropdown = document.getElementById('type');
        var hcpToggleDiv = document.getElementById('forHcp');
        dropdown.addEventListener('change', (event) => {
            if(dropdown.value === '1') {
                hcpToggleDiv.style.display = "block";
            } else {
                hcpToggleDiv.style.display = "none";
            }
        })
    })
</script>
@endpush
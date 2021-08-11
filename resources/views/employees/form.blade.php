@section('head')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
@endsection

<div>
    <div class="card-header bg-transparent ">
        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }} mb-3">
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-folder-17"></i></span>
                </div>
                <input class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Workspace Identifier" type="text" name="code" value="{{ old('code') ?? $employee->workspace_id ?? '' }}" autofocus>
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
                    <option value="1" {{ (old('type', $employee->role->value ?? 0) == 1 ? 'selected' : '') }}>Health Care Provider</option>
                    <option value="2" {{ (old('type', $employee->role->value ?? 0) == 2 ? 'selected' : '') }}>HR</option>
                    <option value="3" {{ (old('type', $employee->role->value ?? 0) == 3 ? 'selected' : '') }}>Company / In-house Clinic</option>
                    <option value="4" {{ (old('type', $employee->role->value ?? 0) == 4 ? 'selected' : '') }}>Employee</option>
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
                        <option value="{{ $type->id }}" {{ (old('role', $employee->hcp_data->type_id ?? 0) == $type->id ? 'selected' : '') }}>{{ $type->name }}</option>
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
                    <input class="form-control{{ $errors->has('prc_id') ? ' is-invalid' : '' }}" placeholder="" type="text" name="prc_id" value="{{ old('prc_id') ?? $employee->hcp_data->prc_id ?? '' }}" autofocus>
                </div>
                @if ($errors->has('prc_id'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('prc_id') }}</strong>
                </span>
                @endif
            </div>
            @if(Route::is('register') || Auth::check())
            <div class="form-group{{ $errors->has('selfie') ? ' has-danger' : '' }}">
                <div class="custom-file">
                    <input type="file" name="selfie" class="custom-file-input" id="selfie">
                    <label class="custom-file-label" for="selfie">Attach your selfie with your PRC ID.</label>
                </div>

                @if ($errors->has('selfie'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('selfie') }}</strong>
                </span>
                @endif
            </div>
            @endif
        </div>
    </div>
    <div class="card-body pb-lg-5">
        @if(!Auth::check() || (Auth::check() && auth()->user()->is_onboarded == 1))
        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                </div>
                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" type="text" name="name" value="{{ old('name') ?? $employee->name ?? '' }}" required autofocus>
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
                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') ?? $employee->email ?? '' }}" required>
            </div>
            @if ($errors->has('email'))
            <span class="invalid-feedback" style="display: block;" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
            @endif
        </div>
        @endif
        <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-world-2"></i></span>
                </div>
                <input class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="Address" type="text" name="address" value="{{ old('address') ?? $employee->address ?? '' }}" required autofocus>
            </div>
            @if ($errors->has('address'))
            <span class="invalid-feedback" style="display: block;" role="alert">
                <strong>{{ $errors->first('address') }}</strong>
            </span>
            @endif
        </div>
        @if(!Auth::check() || (Auth::check() && auth()->user()->is_onboarded == 1))
        <div class="form-group{{ $errors->has('dob') ? ' has-danger' : '' }}">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-watch-time"></i></span>
                </div>
                <input class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}" placeholder="Date of Birth" type="date" name="dob" value="{{ old('dob') ?? $employee->dob ?? '' }}" required autofocus>
            </div>
            @if ($errors->has('dob'))
            <span class="invalid-feedback" style="display: block;" role="alert">
                <strong>{{ $errors->first('dob') }}</strong>
            </span>
            @endif
        </div>
        @endif
        <div class="form-group{{ $errors->has('gender') ? ' has-danger' : '' }}">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="gender">I am a</label>
                </div>
                <select class="custom-select" id="gender" name="gender">
                    <option disabled>Choose...</option>
                    <option value="0" {{ (old('gender', $employee->gender->value ?? 0) == 0 ? 'selected' : '') }}>Male</option>
                    <option value="1" {{ (old('gender', $employee->gender->value ?? 0) == 1 ? 'selected' : '') }}>Female</option>
                    <option value="2" {{ (old('gender', $employee->gender->value ?? 0) == 2 ? 'selected' : '') }}>Others</option>
                </select>
            </div>
            @if ($errors->has('gender'))
            <span class="invalid-feedback" style="display: block;" role="alert">
                <strong>{{ $errors->first('gender') }}</strong>
            </span>
            @endif
        </div>
        @if(!Auth::check() || (Auth::check() && auth()->user()->is_onboarded == 1))
        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                </div>
                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" type="password" name="password">
            </div>
            @if ($errors->has('password'))
            <span class="invalid-feedback" style="display: block;" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
        </div>

        <!-- Time Picker -->
        <!-- <div class="form-group">
            <div class='input-group date' id='datetimepicker3'>
               <input type='text' class="form-control timepicker" />
            </div>
         </div> -->

        <div class="form-group">
            <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                </div>
                <input class="form-control" placeholder="{{ __('Confirm Password') }}" type="password" name="password_confirmation">
            </div>
        </div>
        @endif
        @if(Route::is('register'))
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
        @elseif(Auth::check() && auth()->user()->is_onboarded == 0)
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
        @endif
    </div>
</div>

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
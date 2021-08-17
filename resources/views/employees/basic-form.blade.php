<div>
    <div class="row">
        <div class="col-5">
            <div class="form-group{{ $errors->has('first_name') ? ' has-danger' : '' }}">
                <div class="input-group input-group-alternative mb-3">
                    <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" placeholder="First Name" type="text" name="first_name" value="{{ old('first_name') ?? $employee->first_name ?? '' }}" required autofocus>
                </div>
                @if ($errors->has('first_name'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="col">
            <div class="form-group{{ $errors->has('middle_name') ? ' has-danger' : '' }}">
                <div class="input-group input-group-alternative mb-3">
                    <input class="form-control{{ $errors->has('middle_name') ? ' is-invalid' : '' }}" placeholder="M.I." type="text" name="middle_name" value="{{ old('middle_name') ?? $employee->middle_name ?? '' }}" autofocus>
                </div>
                @if ($errors->has('middle_name'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('middle_name') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="col-5">
            <div class="form-group{{ $errors->has('last_name') ? ' has-danger' : '' }}">
                <div class="input-group input-group-alternative mb-3">
                    <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" placeholder="Last Name" type="text" name="last_name" value="{{ old('last_name') ?? $employee->last_name ?? '' }}" required autofocus>
                </div>
                @if ($errors->has('last_name'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group{{ $errors->has('street_address') ? ' has-danger' : '' }}">
                <div class="input-group input-group-alternative mb-3">
                    <input class="form-control{{ $errors->has('street_address') ? ' is-invalid' : '' }}" placeholder="Street Address" type="text" name="street_address" value="{{ old('street_address') ?? $employee->street_address ?? '' }}" required autofocus>
                </div>
                @if ($errors->has('street_address'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('street_address') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="col">
            <div class="form-group{{ $errors->has('barangay') ? ' has-danger' : '' }}">
                <div class="input-group input-group-alternative mb-3">
                    <input class="form-control{{ $errors->has('barangay') ? ' is-invalid' : '' }}" placeholder="Barangay" type="text" name="barangay" value="{{ old('barangay') ?? $employee->barangay ?? '' }}" required autofocus>
                </div>
                @if ($errors->has('barangay'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('barangay') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group{{ $errors->has('city') ? ' has-danger' : '' }}">
                <div class="input-group input-group-alternative mb-3">
                    <input class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" placeholder="City/Province" type="text" name="city" value="{{ old('city') ?? $employee->city ?? '' }}" required autofocus>
                </div>
                @if ($errors->has('city'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('city') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="col">
            <div class="form-group{{ $errors->has('region') ? ' has-danger' : '' }}">
                <div class="input-group input-group-alternative mb-3">
                    <input class="form-control{{ $errors->has('region') ? ' is-invalid' : '' }}" placeholder="Region" type="text" name="region" value="{{ old('region') ?? $employee->region ?? '' }}" required autofocus>
                </div>
                @if ($errors->has('region'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('region') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>
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
</div>
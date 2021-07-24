<div>
    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
        <div class="input-group input-group-alternative mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-building"></i></span>
            </div>
            <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Business Name" type="text" name="name" value="{{ old('name') ?? $company->name ?? '' }}" required autofocus>
        </div>
        @if ($errors->has('name'))
        <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('employer') ? ' has-danger' : '' }}">
        <div class="input-group input-group-alternative mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
            </div>
            <input class="form-control{{ $errors->has('employer') ? ' is-invalid' : '' }}" placeholder="Employer Name" type="text" name="employer" value="{{ old('employer') ?? $company->employer ?? '' }}" required autofocus>
        </div>
        @if ($errors->has('employer'))
        <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $errors->first('employer') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('contact') ? ' has-danger' : '' }}">
        <div class="input-group input-group-alternative mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
            </div>
            <input class="form-control{{ $errors->has('contact') ? ' is-invalid' : '' }}" placeholder="Contact Phone Number" type="phone" name="contact" value="{{ old('contact') ?? $company->contact ?? '' }}" required autofocus>
        </div>
        @if ($errors->has('contact'))
        <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $errors->first('contact') }}</strong>
        </span>
        @endif
    </div>
</div>
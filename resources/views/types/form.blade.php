<div>
    <div class="form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
        <div class="input-group input-group-alternative mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="type">Type for...</label>
            </div>
            <select class="custom-select" id="type" name="type">
                <option disabled>Choose...</option>
                <option value="0" {{ (old('type', $type->type ?? 0) == 0 ? 'selected' : '') }}>Health Care Provider</option>
                <option value="1" {{ (old('type', $type->type ?? 0) == 1 ? 'selected' : '') }}>Tests</option>
                <option value="2" {{ (old('type', $type->type ?? 0) == 2 ? 'selected' : '') }}>Services</option>
            </select>
        </div>
        @if ($errors->has('type'))
        <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $errors->first('type') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
        <div class="input-group input-group-alternative mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-building"></i></span>
            </div>
            <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Name" type="text" name="name" value="{{ old('name') ?? $type->name ?? '' }}" required autofocus>
        </div>
        @if ($errors->has('name'))
        <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
        @endif
    </div>
</div>
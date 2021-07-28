@if($errors->any())
    {!! implode('', $errors->all('<div>:message</div>')) !!}
@endif
<div>
    <div class="card-header bg-transparent ">
    </div>
    <div class="card-body pb-lg-5">
        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                </div>
                <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" type="text" name="name" value="{{ old('name') ?? $item->name ?? '' }}" required autofocus>
            </div>
            @if ($errors->has('name'))
            <span class="invalid-feedback" style="display: block;" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                </div>
                <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="Item Description" type="text" name="description" value="{{ old('description') ?? $item->description ?? '' }}" autofocus>
            </div>
            @if ($errors->has('description'))
            <span class="invalid-feedback" style="display: block;" role="alert">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Php</span>
                </div>
                <input class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="Item Price" type="number" name="price" value="{{ old('price') ?? $item->price ?? '' }}" required autofocus>
            </div>
            @if ($errors->has('price'))
            <span class="invalid-feedback" style="display: block;" role="alert">
                <strong>{{ $errors->first('price') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('photo') ? ' has-danger' : '' }}">
            <div class="custom-file">
                <input type="file" name="photo" class="custom-file-input" id="photo">
                <label class="custom-file-label" for="photo">Optional: Item Photo</label>
            </div>

            @if ($errors->has('photo'))
            <span class="invalid-feedback" style="display: block;" role="alert">
                <strong>{{ $errors->first('photo') }}</strong>
            </span>
            @endif
        </div>
        <div class="input-group input-group-alternative mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="viewable_as">Viewable as</label>
            </div>
            <select class="custom-select" id="viewable_as" name="viewable_as">
                <option disabled>Choose...</option>
                <option value="1" {{ (old('viewable_as', $item->viewable_as->value ?? 0) == 1 ? 'selected' : '') }}>Health Care Provider</option>
                <option value="2" {{ (old('viewable_as', $item->viewable_as->value ?? 0) == 2 ? 'selected' : '') }}>HR</option>
                <option value="3" {{ (old('viewable_as', $item->viewable_as->value ?? 0) == 3 ? 'selected' : '') }}>Company / In-house Clinic</option>
                <option value="4" {{ (old('viewable_as', $item->viewable_as->value ?? 0) == 4 ? 'selected' : '') }}>Employee</option>
            </select>
        </div>
    </div>
</div>
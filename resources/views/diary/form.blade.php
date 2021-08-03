<div>
    <div class="card-header bg-transparent ">
    </div>
    <div class="card-body pb-lg-5">
        @if(auth()->user()->role->value == 0 || auth()->user()->role->value == 1)
        <div class="input-group input-group-alternative mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="user_id">Select a user</label>
            </div>
            <select class="custom-select" id="user_id" name="user_id">
                <option disabled>Choose...</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ (old('user_id', $user->id ?? 0) == $user->id ? 'selected' : '') }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="form-group{{ $errors->has('note') ? ' has-danger' : '' }}">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-single-copy-04"></i></span>
                </div>
                <input class="form-control{{ $errors->has('note') ? ' is-invalid' : '' }}" placeholder="Note" type="text" name="note" value="{{ old('note') ?? $item->note ?? '' }}" required autofocus>
            </div>
            @if ($errors->has('note'))
            <span class="invalid-feedback" style="display: block;" role="alert">
                <strong>{{ $errors->first('note') }}</strong>
            </span>
            @endif
        </div>
    </div>
</div>
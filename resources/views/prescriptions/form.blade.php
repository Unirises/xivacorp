<div>
    <div class="form-group{{ $errors->has('referral') ? ' has-danger' : '' }}">
        <div class="input-group input-group-alternative mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-user-run"></i></span>
            </div>
            <input class="form-control{{ $errors->has('referral') ? ' is-invalid' : '' }}" placeholder="Referral to" type="text" name="referral" value="{{ old('referral') ?? $prescription->referral ?? '' }}" autofocus>
        </div>
        @if ($errors->has('referral'))
        <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $errors->first('referral') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('prescription') ? ' has-danger' : '' }}">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Prescription</span>
            </div>
            <textarea class="form-control{{ $errors->has('prescription') ? ' is-invalid' : '' }}" aria-label="Prescription" name="prescription" autofocus>{{ old('prescription') ?? $prescription->prescription ?? '' }}</textarea>

        </div>
        @if ($errors->has('prescription'))
        <span class="invalid-feedback" style="display: block;" role="alert">
            <strong>{{ $errors->first('prescription') }}</strong>
        </span>
        @endif
    </div>
</div>
<div>
    <div class="row">
        <div class="col">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="provider">Provider</label>
                </div>
                <select class="custom-select" id="provider" name="provider" wire:model="provider">
                    <option>Choose...</option>
                    @foreach($providers as $provider)
                    <option value="{{ $provider->id }}" {{ (old('provider', $provider->id ?? 0) == $provider->id ? 'selected' : '') }}>{{ $provider->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col">
            <div class="form-group{{ $errors->has('date') ? ' has-danger' : '' }}">
                <input type="text" name="date" id="date" class="form-control form-control-alternative{{ $errors->has('date') ? ' is-invalid' : '' }}" placeholder="Select a Date" value="{{ old('date') }}" required wire:model="date">

                @if ($errors->has('date'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('date') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="col">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="schedule">Time</label>
                </div>
                <select class="custom-select" id="schedule" name="schedule">
                    <option disabled>Choose...</option>
                </select>
            </div>
        </div>
    </div>
</div>

@push('js')
@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    $("#date").flatpickr({
        dateFormat: 'Y-m-d',
        minDate: "today",
    });

    Livewire.on('updateDays', data => {
        $("#date").flatpickr({
            dateFormat: 'Y-m-d',
            minDate: "today",
            disable: [
                function(date) {
                    return data.some(function(e) {
                        return date.getDay() === e;
                    });
                }
            ]
        });
    })

    Livewire.on('updateHours', data => {
        Object.values(data).forEach((el) => {
            const date = new Date(el).toLocaleString("en-US", {timeZone: "Asia/Manila"});
            if(moment().isAfter(date)) { return; };
            $("#schedule").append(new Option(moment(date).format('LT'),date))
        })
    })
</script>
@endpush
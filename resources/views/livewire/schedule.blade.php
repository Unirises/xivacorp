<div>
    <div class="row">
        <div class="col">
            <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="provider">Select a provider</label>
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
                <label class="form-control-label" for="input-email">Date</label>
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
                    <label class="input-group-text" for="schedule">Select a schedule</label>
                </div>
                <select class="custom-select" id="schedule" name="schedule">
                    <option disabled>Choose...</option>
                </select>
            </div>
            <!-- <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="schedule">Select a schedule</label>
                </div>
                <select class="custom-select" id="schedule" name="schedule">
                    <option disabled>Choose...</option>
                    @foreach(\Carbon\CarbonInterval::minutes(30)->toPeriod('2:00 PM', '11:59 PM') as $schedule)
                    <option value="{{ $schedule }}" {{ (old('schedule', $schedule ?? null) == $schedule ? 'selected' : '') }}>{{ $schedule }}</option>
                    @endforeach
                </select>
            </div> -->
        </div>
    </div>
</div>

@push('js')
@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $("#date").flatpickr({
        dateFormat: 'Y-m-d',
    });

    Livewire.on('updateDays', data => {
        $("#date").flatpickr({
            dateFormat: 'Y-m-d',
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
        data.forEach((el) => {
            $("#schedule").append(new Option(el,el))
        })
    })
</script>
@endpush
@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
            <div class="card card-profile shadow">
                <div class="card-body pt-0 pt-md-4">
                    <div class="text-center">
                        <h3>
                            {{ auth()->user()->name }}
                        </h3>
                        <div class="h5 font-weight-300">
                            <i class="ni location_pin mr-2"></i>{{ auth()->user()->company->name ?? 'Floating' }}
                        </div>
                        <div class="h5 mt-4">
                            <i class="ni business_briefcase-24 mr-2"></i>
                            @if(auth()->user()->hcp_data)
                            {{ auth()->user()->hcp_data->type->name }} - {{ auth()->user()->hcp_data->prc_id }}
                            @else
                            {{ strtoupper(auth()->user()->role->description)  }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0">{{ __('Edit Profile') }}</h3>
                    </div>
                </div>
                <div class="card-body">
                    @if(auth()->user()->role->value == 1)
                    <form action="{{ route('profile.hours') }}" method="post">
                        @csrf
                        <h6 class="heading-small text-muted mb-4">Working Hours</h6>

                        @if ($errors->has('days'))
                        <div class="alert alert-danger" role="alert">
                            You need to select your working days.
                        </div>
                        @endif

                        @if (\Session::has('success'))
                        <div class="alert alert-success">
                            {!! \Session::get('success') !!}</li>
                        </div>
                        @endif

                        @if(auth()->user()->hours)
                        <div class="alert alert-info" role="alert">
                            <strong>Current set schedule</strong>
                            <ul>
                                @foreach(json_decode(auth()->user()->hours) as $day => $hour)
                                <li>{{ ucwords($day) }} - {{ ucwords($hour[0]) }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="monday" name="days[]" value="monday">
                            <label class="form-check-label" for="monday">Monday</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="tuesday" name="days[]" value="tuesday">
                            <label class="form-check-label" for="tuesday">Tuesday</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="wednesday" name="days[]" value="wednesday">
                            <label class="form-check-label" for="wednesday">Wednesday</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="thursday" name="days[]" value="thursday">
                            <label class="form-check-label" for="thursday">Thursday</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="friday" name="days[]" value="friday">
                            <label class="form-check-label" for="friday">Friday</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="saturday" name="days[]" value="saturday">
                            <label class="form-check-label" for="saturday">Saturday</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="sunday" name="days[]" value="sunday">
                            <label class="form-check-label" for="sunday">Sunday</label>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group{{ $errors->has('start') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">Start Time</label>
                                    <input type="text" name="start" id="start" class="form-control form-control-alternative{{ $errors->has('start') ? ' is-invalid' : '' }}" placeholder="Starting Time" value="{{ old('start') }}" required>

                                    @if ($errors->has('start'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('start') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group{{ $errors->has('end') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">End Time</label>
                                    <input type="text" name="end" id="end" class="form-control form-control-alternative{{ $errors->has('end') ? ' is-invalid' : '' }}" placeholder="End Time" value="{{ old('end') }}" required>

                                    @if ($errors->has('end'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('end') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-4">Update Working Hours</button>
                        </div>
                    </form>
                    <hr class="my-4" />
                    <form id="my-form">
                        @csrf
                        <h6 class="heading-small text-muted mb-4">Signature</h6>
                        <canvas width="664" style="touch-action: none;" height="373"></canvas>
                        <div class="row">
                            <div class="col">
                                <span class="text-gray">Sign Above</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-4">Update Signature</button>
                        </div>
                    </form>
                    <hr class="my-4" />
                    @endif
                    <form method="POST" action="{{ route('profile.info') }}">
                        @csrf
                        @method('PUT')
                        @include('employees.basic-form')
                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-4">Update Info</button>
                        </div>
                    </form>
                    <hr class="my-4" />
                    <form method="POST" action="{{ route('profile.email') }}">
                        @csrf
                        @method('PUT')
                        <div class="pl-lg-4">
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-email">Email</label>
                                <input type="email" name="email" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email" value="{{ old('email', auth()->user()->email) }}" required>

                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">Update Email</button>
                            </div>
                        </div>
                    </form>
                    <hr class="my-4" />
                    <form method="post" action="{{ route('profile.password') }}" autocomplete="off">
                        @csrf
                        @method('put')

                        <h6 class="heading-small text-muted mb-4">{{ __('Password') }}</h6>

                        @if (session('password_status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('password_status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <div class="pl-lg-4">
                            <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-current-password">{{ __('Current Password') }}</label>
                                <input type="password" name="old_password" id="input-current-password" class="form-control form-control-alternative{{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Current Password') }}" value="" required>

                                @if ($errors->has('old_password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('old_password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-password">{{ __('New Password') }}</label>
                                <input type="password" name="password" id="input-password" class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" value="" required>

                                @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="input-password-confirmation">{{ __('Confirm New Password') }}</label>
                                <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control form-control-alternative" placeholder="{{ __('Confirm New Password') }}" value="" required>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">{{ __('Change password') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth')
</div>
@endsection


@push('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script>
    $("#start").flatpickr({
        enableTime: true,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i",
    });
    $("#end").flatpickr({
        enableTime: true,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i",
    });
    jQuery(function($) {
        var canvas = document.querySelector("canvas");
        if (canvas) {
            var signaturePad = new SignaturePad(canvas);
        }

        $('#my-form').on('submit', async function(e) {
            e.preventDefault();

            fetch("{{ route('profile.signature') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    signature: canvas != null ? signaturePad.toDataURL() : null,
                })
            }).then(async (resp) => {
                var respData = await resp.text();
                if (resp.status == 422) {
                    return alert('Please fill in all the fields.');
                }

                alert('Your signature has been updated!');
            }).catch((err) => {
                console.error(err);
                alert('There was a problem saving your data.');
            });

            return false;
        });
    });
</script>
@endpush
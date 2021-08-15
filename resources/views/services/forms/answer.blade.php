@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@extends('layouts.app')
@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <form id="my-form">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">{{ $form->name }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="fb-render"></div>
                        @if(auth()->user()->role->value == 1)
                        <div class="form-group{{ $errors->has('photo') ? ' has-danger' : '' }}">
                            <div class="custom-file">
                                <input type="file" name="photo" class="custom-file-input" id="photo">
                                <label class="custom-file-label" for="photo" id="photo_label">Optional: Result Photo</label>
                            </div>

                            @if ($errors->has('photo'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('photo') }}</strong>
                            </span>
                            @endif
                        </div>
                        @if($form->is_exportable)
                        <div class="form-group{{ $errors->has('doctor_name') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <input class="form-control{{ $errors->has('doctor_name') ? ' is-invalid' : '' }}" placeholder="Doctor Name Currently on Duty" type="text" name="doctor_name" id="doctor_name" value="{{ old('doctor_name') ?? $employee->doctor_name ?? '' }}" required autofocus>
                            </div>
                            @if ($errors->has('doctor_name'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('doctor_name') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('doctor_prc') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative mb-3">
                                <input class="form-control{{ $errors->has('doctor_prc') ? ' is-invalid' : '' }}" placeholder="Doctor PRC ID" type="text" name="doctor_prc" id="doctor_prc" value="{{ old('doctor_prc') ?? $employee->doctor_prc ?? '' }}" required autofocus>
                            </div>
                            @if ($errors->has('doctor_prc'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('doctor_prc') }}</strong>
                            </span>
                            @endif
                        </div>
                        @endif
                        @endif
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">Submit Answer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
<script src="https://formbuilder.online/assets/js/form-render.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script>
    jQuery(function($) {
        const toBase64 = file => new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
        });

        var canvas = document.querySelector("canvas");
        if (canvas) {
            var signaturePad = new SignaturePad(canvas);
        }

        var fbTemplate = document.getElementById('fb-template');
        var formBuilder = $('.fb-render').formRender({
            dataType: 'json',
            formData: JSON.parse(`{{ $form->data }}`.replace(/&quot;/g, '\"'))
        });

        $("#photo").change(function() {
            $("#photo_label").html($(this).val().split("\\").splice(-1, 1)[0] || "Optional: Result Photo");
        });

        $('#my-form').on('submit', async function(e) {
            e.preventDefault();
            console.clear();
            var formData = $(this).serializeArray();
            const photo = document.querySelector('#photo')?.files[0];
            var parsedFields = [];
            formData.forEach((el) => {
                console.log(el);
                var forLabelName = el.name.replace("[]", "");
                var label = document.querySelectorAll(`label[for="${forLabelName}"]`);
                label = label.length > 0 ? label[0].innerHTML : 'Hidden Field';

                parsedFields.push({
                    name: el.name,
                    label: label,
                    value: el.value
                });
            });

            fetch("{{ route('services.forms.store', [$serviceId, $form->id]) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    data: parsedFields,
                    signature: canvas != null ? signaturePad.toDataURL() : null,
                    photo: photo != undefined ? await toBase64(photo) : null,
                    doctor_name: document.getElementById('doctor_name')?.value,
                    doctor_prc: document.getElementById('doctor_prc')?.value,
                })
            }).then(async (resp) => {
                var respData = await resp.text();
                if (resp.status == 422) {
                    return alert('Please fill in all the fields.');
                }

                window.history.back();
            }).catch((err) => {
                console.error(err);
                alert('There was a problem saving your data.');
            });
        });
    });
</script>
@endpush
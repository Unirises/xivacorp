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
                        <canvas width="664" style="touch-action: none;" height="373"></canvas>
                        <div class="row">
                            <div class="col">
                                <span class="text-gray">Sign Above</span>
                            </div>
                        </div>
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
        var canvas = document.querySelector("canvas");
        if(canvas) {
            var signaturePad = new SignaturePad(canvas);
        }
        
        var fbTemplate = document.getElementById('fb-template');
        var formBuilder = $('.fb-render').formRender({
            dataType: 'json',
            formData: JSON.parse(`{{ $form->data }}`.replace(/&quot;/g, '\"'))
        });
        $('#my-form').on('submit', function(e) {
            e.preventDefault();
            console.clear();
            var formData = $(this).serializeArray();
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

            fetch("{{ Route::has('consultations') ? route('consultations.forms.store', [$consultationId, $formId, $userId]) : route('services.forms.store', [$consultationId, $formId, $userId]) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    data: parsedFields,
                    signature: canvas != null ? signaturePad.toDataURL() : null,
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

            return false;
        });
    });
</script>
@endpush
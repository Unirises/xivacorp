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
                    </div>
                    <!-- Card footer -->
                    <!-- <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">Submit Answer</button>
                    </div> -->
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
<script>
    jQuery(function($) {
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

            fetch("{{ route('submit-answer') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    form_id: "{{ $form->id }}",
                    user_id: $("select#user option").filter(":selected").val() ?? "{{ auth()->user()->id }}",
                    data: parsedFields
                })
            }).then(async (resp) => {
                var respData = await resp.text();
                console.log(respData);
                if (resp.status == 422) {
                    return alert('Please fill in all the fields.');
                }

                window.location.replace(respData);
            }).catch((err) => {
                console.error(err);
                alert('There was a problem saving your data.');
            });

            return false;
        });
    });
</script>
@endpush
@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Scan your QR Code</h3>
                </div>
                <!-- Light table -->
                <div class="card-body">
                    <div id="reader" width="600px"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h1 class="text-center">Name:</h1>
                <b class="text-center">
                    <h1 id="client_name">Juan Dela Cruz</h1>
                </b>
                <h1 class="text-center">Report #:</h1>
                <b class="text-center">
                    <h1 id="report_id">*****</h1>
                </b>
                <h1 class="text-center">Date Performed:</h1>
                <b class="text-center">
                    <h1 id="date_performed">June 08, 2021</h1>
                </b>
                <h1 class="text-center">Findings:</h1>
                <b>
                    <div id="findings"></div>
                </b>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="" id="res_download" class="btn btn-primary">Download Report</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.0.3/html5-qrcode.min.js" integrity="sha512-uOj9C1++KO/GY58nW0CjDiUjLKWQG4yB/NJMj3PtJNmFA52Hg56lojRtvBpLgQyVByUD+1M3M/1tKdoGDKUBAQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        fetch(`verify-qr/${decodedText.split("/").pop()}`).then(async (data) => {
            var respData = (await data.json())['data'];

            $("#client_name").text(respData['service']['client']['name']);
            $("#report_id").text(respData['custom_id']);
            $("#date_performed").text(respData['done_date']);
            console.log(respData);

            //     var table = $("<table />");
            //     table[0].border = "1";
            //     var columnCount = respData['answer'].length;

            //     var row = $(table[0].insertRow(-1));
            //     for (var i = 0; i < columnCount; i++) {
            //         var headerCell = $("<th />");
            //         headerCell.html(respData['answer'][i]['label']);
            //         row.append(headerCell);
            //     }

            //     //Add the data rows.
            //     for (var i = 1; i < respData['answer'].length; i++) {
            //         row = $(table[0].insertRow(-1));
            //         var cell = $("<td />");
            //         cell.html(respData['answer'][i]['value']);
            //         row.append(cell);
            //     }

            //     var dvTable = $("#findings");
            // dvTable.html("");
            // dvTable.append(table);

            var sub_ul = $('<ul/>');
            respData['answer'].forEach(element => {
                var sub_li = $('<li/>').html(element['label'] + ' – ' + element['value']);
                sub_ul.append(sub_li);
            });

            var dvTable = $("#findings");
            dvTable.html("");
            dvTable.append(sub_ul);

            $("#res_download").attr("href", `/services/${respData['service']['id']}/forms/${respData['id']}/export/`);
            $('#exampleModal').modal('show');
        })
    }

    function onScanFailure(error) {}

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", {
            fps: 10,
            qrbox: 300
        }, /* verbose= */ false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush
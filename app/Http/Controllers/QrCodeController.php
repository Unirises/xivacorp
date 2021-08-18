<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceForms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class QrCodeController extends Controller
{
    public function index()
    {
        return view('layouts.verify-qr');
    }

    public function fetch(string $data)
    {
        $x = explode('-', $data);
        $y = end($x);

        $form = ServiceForms::with('service', 'service.client')->findOrFail($y);
        $form->custom_id = $form->service->workspace_id . "-" . $form->service_id . $form->form_id . $form->answerable_by . '-'. $form->id;
        $form->done_date = Carbon::parse($form->updated_at)->toDayDateTimeString();
        $form->answer = json_decode($form->answer);

        return response()->json([
            'data' => $form
        ]);
    }

    public function show(string $data)
    {
        $x = explode('-', $data);
        $y = end($x);

        $form = ServiceForms::with('service', 'service.client')->findOrFail($y);
        $form->answer = json_decode($form->answer);
        return view('layouts.show-qr', compact('form'));
    }
}

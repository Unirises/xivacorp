<?php

namespace App\Http\Controllers;

use App\Models\ConsultationForm;
use App\Models\Form;
use Illuminate\Http\Request;

class ConsultationFormController extends Controller
{
    public function edit(int $consultationId, int $formId, int $userId)
    {
        $form = Form::findOrFail($formId);
        $form['data'] = json_decode($form['data']);

        return view('coforms.edit', compact('consultationId', 'formId', 'userId', 'form'));
    }

    public function store(Request $request, int $consultationId, int $formId, int $userId)
    {
        $validated =  $this->validate($request, [
            'data' => 'required|array',
            'data.*.name' => 'nullable|string',
            'data.*.label' => 'nullable|string',
            'data.*.value' => 'nullable|string',
        ]);

        ConsultationForm::where('consultation_id', $consultationId)->where('form_id', $formId)->where('answerable_by', $userId)->update([
            'data' => json_encode($validated['data'])
        ]);

        return redirect()->back();
    }

    public function show(int $consultationId, int $formId, int $userId)
    {
        $data = ConsultationForm::where('consultation_id', $consultationId)->where('form_id', $formId)->where('answerable_by', $userId)->first();
        $form = Form::findOrFail($formId);
        abort_if(!$data, 404);
        $data->data = json_decode($data['data']);
        return view('coforms.show', compact('consultationId', 'formId', 'userId', 'form'))->with('answer', $data);
    }
}

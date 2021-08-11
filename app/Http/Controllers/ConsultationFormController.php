<?php

namespace App\Http\Controllers;

use App\Models\ConsultationForm;
use App\Models\Form;
use App\Models\HcpData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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
            'signature' => 'nullable|string',
            'photo' => 'nullable|string'
        ]);

        if($request->filled('photo')) {
            $filename =  'result-' . $consultationId . '-'. $formId .'-'. $userId . '.' . 'png';
            $image = $validated['photo'];
            $imageInfo = explode(";base64,", $image);  
            $image = str_replace(' ', '+', $imageInfo[1]);
            Storage::disk('local')->put('public/results/' . $filename, base64_decode($image));
            $validated['photo'] = $filename;
        }

        ConsultationForm::where('consultation_id', $consultationId)->where('form_id', $formId)->where('answerable_by', $userId)->update([
            'data' => json_encode($validated['data']),
            'signature' => $validated['signature'] ?? null,
            'photo' => $request->filled('photo') ? $validated['photo'] : null,
        ]);

        return redirect()->back();
    }

    public function show(int $consultationId, int $formId, int $userId)
    {
        $data = ConsultationForm::where('consultation_id', $consultationId)->where('form_id', $formId)->where('answerable_by', $userId)->first();
        $form = Form::findOrFail($formId);
        $hcpData = HcpData::select('signature')->where('user_id', $userId)->firstOrFail();
        abort_if(!$data, 404);
        $data->data = json_decode($data['data']);
        return view('coforms.show', compact('consultationId', 'formId', 'userId', 'form', 'hcpData'))->with('answer', $data);
    }

    function base64_to_jpeg($base64_string, $output_file)
    {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' ); 
    
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );
    
        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    
        // clean up the file resource
        fclose( $ifp ); 
    
        return $output_file; 
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Form;
use App\Models\HcpData;
use App\Models\Service;
use App\Models\ServiceForms;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HealthServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        switch(auth()->user()->role) {
            case UserRole::Admin():
            case UserRole::CoAdmin():
                $bookings = Service::all();
                break;
            case UserRole::Clinic():
            case UserRole::HR():
            case UserRole::HCP():
                $bookings = Service::where('workspace_id', auth()->user()->workspace_id)->get();
                break;
            case UserRole::Employee():
                $bookings = Service::where('user_id', auth()->user()->id)->get();
                break;
        }
        $bookings = $bookings->sortByDesc('created_at');
        return view('services.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $workspaceId = auth()->user()->workspace_id;
        $services = Type::where('type', '!=', 0)->get()->sortBy('name')->sortBy('type');
        $users =  auth()->user()->role->value == 0 ? User::where('role', 4)->whereNotNull('workspace_id')->get()->sortBy('name') : User::where('workspace_id', $workspaceId)->whereIn('role', [2, 3, 4])->get()->sortBy('name');
        return view('services.create', compact('services', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'schedule' => 'nullable|date',
            'service_id' => 'required|exists:types,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if(!$request->has('user_id')) {
            $validated['user_id'] = auth()->user()->id;
        }

        $selectedUser = User::findOrFail($validated['user_id']);
        
        Service::create([
            'user_id' => $validated['user_id'],
            'workspace_id' => $selectedUser->workspace_id,
            'service_id' => $validated['service_id'],
            'schedule' => $request->has('is_continuous') ? null : Carbon::parse($validated['schedule']),
            'pending' => $request->has('is_continuous') ? false : true,
        ]);

        return redirect()->route('services.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        $forms = Form::whereIn('owner_id', [auth()->user()->id, 1])->get();
        $available_forms = ServiceForms::where('service_id', $id)->get();
        return view('services.show', compact('forms', 'service', 'available_forms'))->with('current_id', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::where('id', $id)->first();
        if(auth()->user()->role->value == 0) {
            $service->forms()->delete();
            $service->delete();
        } else if ($service->hcp_id != null && $service->hcp_id == auth()->user()->id) {
            $service->forms()->delete();
            $service->delete();
        } else if ($service->hcp_id == null && $service->workspace_id == auth()->user()->workspace_id) {
            $service->forms()->delete();
            $service->delete();
        } else {
            return abort(401);
        }

        return redirect()->back();
    }
    public function acceptBooking(int $id)
    {
        $booking = Service::findOrFail($id);

        $booking->update([
            'pending' => false,
            'hcp_id' => auth()->user()->id
        ]);

        return redirect()->back();
    }

    public function addNewFormToService(Request $request, int $id)
    {
        $validated = $this->validate($request, [
            'form_id' => 'required|exists:forms,id',
            'user_id' => 'required|exists:users,id',
        ]);

        ServiceForms::create([
            'service_id' => $id,
            'form_id' => $validated['form_id'],
            'answerable_by' => $validated['user_id'],
            'is_exportable' => $request->has('checkbox') && $validated['user_id'] == auth()->user()->id ? true : false,
            'need_signature' => $request->has('client_signature') && $validated['user_id'] != auth()->user()->id ? true : false,
        ]);

        return redirect()->back();
    }

    public function showAnswerForm(int $serviceId, int $formId)
    {
        $form = ServiceForms::findOrFail($formId);
        $data = Form::findOrFail($form->form_id);
        $form->data = json_decode($data->data);
        $form->data = str_replace('\n', "", $form->data);
        $doctors = User::where('workspace_id', auth()->user()->workspace_id)->where('role', 1)->whereNotNull('hours')->with(['hcp_data' => function ($q) {
            $q->where('hcp_data.type_id', '=', 3);
        }])->get();
        return view('services.forms.answer', compact('form', 'serviceId', 'doctors'));
    }
    
    public function storeResponse(Request $request, int $serviceId, int $formId)
    {
        $form = ServiceForms::findOrFail($formId);

        $validated =  $this->validate($request, [
            'data' => 'required|array',
            'data.*.name' => 'nullable|string',
            'data.*.label' => 'nullable|string',
            'data.*.value' => 'nullable|string',
            'photo' => 'nullable|string',
            'doctor_id' => 'nullable|exists:users,id',
            'signature' => 'nullable|string',
        ]);

        if($request->filled('photo')) {
            $filename =  'result-'. $form->id . $serviceId . '-'. $formId .'-'. $form->answerable_by . '.' . 'png';
            $image = $validated['photo'];
            $imageInfo = explode(";base64,", $image);  
            $image = str_replace(' ', '+', $imageInfo[1]);
            Storage::disk('local')->put('public/results/' . $filename, base64_decode($image));
            $validated['photo'] = $filename;
        }

        ServiceForms::where('id', $formId)->update([
            'answer' => json_encode($validated['data']),
            'photo' => $request->filled('photo') ? $validated['photo'] : null,
            'doctor_id' => $validated['doctor_id'] ?? null,
            'signature' => $validated['signature'] ?? null,
        ]);
        return redirect()->back();
    }
    
    public function showResponse(int $serviceId, int $formId)
    {
        $form = ServiceForms::findOrFail($formId);
        $form->answer = json_decode($form->answer);
        $hcpData = HcpData::select('signature')->where('user_id', $form->answerable_by)->first();
        return view('services.forms.response', compact('form', 'hcpData', 'serviceId'));
    }
    
    public function exportResponse(int $serviceId, int $formId)
    {
        $form = ServiceForms::findOrFail($formId);
        $data = json_decode($form->answer);
        $values = [];
        abort_if($data == null, 422);
        foreach($data as $datum) {
            if($datum->label == 'Hidden Field') break;
            array_push($values, ['res_name' => str_replace('\n', "", str_replace("&nbsp;", "", strip_tags($datum->label))) , 'res_val' => $datum->value]);
        }
        $id = $form->service->workspace_id . "-" . $form->service_id . $form->form_id . $form->answerable_by . '-' . $form->id;
        $url = public_path('storage/results/qrcode/'.$id.'.png');
        QrCode::size(175)->format('png')->generate(route('qr.view', $id), $url);

        $image = $form->answerer->hcp_data->signature;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $form->answerer->id.'.'.'png';
        Storage::disk('local')->put('public/hcp/signature/' . $imageName,  base64_decode($image));

        $image1 = $form->doctor->hcp_data->signature;  // your base64 encoded
        $image1 = str_replace('data:image/png;base64,', '', $image1);
        $image1 = str_replace(' ', '+', $image1);
        $imageName1 = $form->doctor->id.'.'.'png';
        Storage::disk('local')->put('public/hcp/signature/' . $imageName1,  base64_decode($image1));

        $templateProcessor = new TemplateProcessor('word-template/result.docx');
        $templateProcessor->cloneRowAndSetValues('res_name', $values);

        $templateProcessor->setValues([
            'c_name' => $form->service->client->name,
            'c_dob' => $form->service->client->dob,
            'c_age' => Carbon::parse($form->service->client->dob)->age,
            'date' => Carbon::parse($form->updated_at)->format('m/d/Y g:i A'),
            'c_gender' => $form->service->client->gender->description,
            'id' => $id,
            'hcp_name' => $form->answerer->name,
            'hcp_prc' => $form->answerer->hcp_data->prc_id,
            'doctor_fullname' => $form->doctor->name,
            'doctor_prc' => $form->doctor->hcp_data->prc_id,
            'form_name' => $form->form->name
        ]);
        if($form->photo) {
            $templateProcessor->setImageValue('photo', ['path' => $form->downloadable_photo_url, 'width' => 150, 'height' => 150, 'ratio' => false]);
        } else {
            $templateProcessor->setValue('photo', '');
        }
        $templateProcessor->setImageValue('qr_code', ['path' => $url, 'width' => 100, 'height' => 100, 'ratio' => false]);
        $templateProcessor->setImageValue('hcp_signature', ['path' => public_path('storage/hcp/signature/'.$imageName), 'ratio' => false]);
        $templateProcessor->setImageValue('doctor_signature', ['path' => public_path('storage/hcp/signature/'.$imageName1), 'ratio' => false]);
        $fileName = $id.'.docx';
        $templateProcessor->saveAs($fileName);
        return response()->download($fileName)->deleteFileAfterSend(true);;
    }

    public function deleteForm(int $serviceId, int $formId)
    {
        $form = ServiceForms::findOrFail($formId);
        $form->delete();

        return redirect()->back();
    }

    public function exportAllBookings()
    {
        $bookings = Service::all();
        $bookings = $bookings->sortByDesc('created_at');

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=bookings-". Carbon::now() .".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Workspace ID', 'Client', 'Provider', 'Service', 'Schedule', 'Is Pending for HCP Acceptance');

        $callback = function() use($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($bookings as $consultation) {
                $row['Workspace ID'] = $consultation->workspace_id;
                $row['Client'] = $consultation->client->name;
                $row['Provider'] = $consultation->provider->name ?? 'N/A';
                $row['Service'] = $consultation->service->meta;
                $row['Schedule'] = $consultation->schedule ?? 'Recurring';
                $row['Is Pending'] = $consultation->pending == 1 ? 'Yes' : 'No';

                fputcsv($file, array(
                    $row['Workspace ID'], 
                    $row['Client'], 
                    $row['Provider'], 
                    $row['Service'], 
                    $row['Schedule'], 
                    $row['Is Pending'], 
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

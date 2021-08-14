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
use Illuminate\Support\Facades\Storage;

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
        $services = Type::where('type', '!=', 0)->get();
        $users =  auth()->user()->role->value == 0 ? User::where('role', 4)->whereNotNull('workspace_id')->get() : User::where('workspace_id', $workspaceId)->whereIn('role', [4])->get();
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
        $service = Service::where('id', $id)->get();
        if(auth()->user()->role == 0) {
            $service->delete();
        } else if ($service->hcp_id != null && $service->hcp_id == auth()->user()->id) {
            $service->delete();
        } else if ($service->hcp_id == null && $service->workspace_id == auth()->user()->workspace_id) {
            $service->delete();
        } else {
            return abort(401);
        }

        return redirect()->back();
    }

    public function addNewFormToService(Request $request, int $id)
    {
        $validated = $this->validate($request, [
            'form_id' => 'required|exists:forms,id',
            'user_id' => 'required|exists:users,id'
        ]);


        ServiceForms::create([
            'service_id' => $id,
            'form_id' => $validated['form_id'],
            'answerable_by' => $validated['user_id'],
        ]);

        return redirect()->back();
    }

    public function showAnswerForm(int $serviceId, int $formId)
    {
        $form = ServiceForms::findOrFail($formId);
        $data = Form::findOrFail($form->form_id);
        $form->data = json_decode($data->data);
        return view('services.forms.answer', compact('form', 'serviceId'));
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
            'doctor_name' => 'nullable|string',
            'doctor_prc' => 'nullable|string',
        ]);

        if($request->filled('photo')) {
            $filename =  'result-'. $form->id . $serviceId . '-'. $formId .'-'. $form->answerable_by . '.' . 'png';
            $image = $validated['photo'];
            $imageInfo = explode(";base64,", $image);  
            $image = str_replace(' ', '+', $imageInfo[1]);
            Storage::disk('local')->put('public/results/' . $filename, base64_decode($image));
            $validated['photo'] = $filename;
        }

        $data = ServiceForms::where('id', $formId)->update([
            'answer' => json_encode($validated['data']),
            'photo' => $request->filled('photo') ? $validated['photo'] : null,
            'doctor_name' => $validated['doctor_name'] ?? null,
            'doctor_prc' => $validated['doctor_prc'] ?? null,
        ]);
        return $data;
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

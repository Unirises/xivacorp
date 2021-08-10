<?php

namespace App\Http\Controllers;

use App\Enums\ServiceType;
use App\Enums\TypeIdent;
use App\Enums\UserRole;
use App\Models\Consultation;
use App\Models\ConsultationForm;
use App\Models\Form;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
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
                $consultations = Consultation::where('service_type', '!=', ServiceType::Consultation)->get();
                break;
            case UserRole::CoAdmin():
            case UserRole::Clinic():
            case UserRole::HR():
                $consultations = Consultation::where('service_type', '!=', ServiceType::Consultation)->where('workspace_id', auth()->user()->workspace_id)->get();
                break;
            case UserRole::Employee():
                $consultations = Consultation::where('service_type', '!=', ServiceType::Consultation)->where('user_id', auth()->user()->id)->get();
                break;
            case UserRole::HCP():
                $consultations = Consultation::where('service_type', '!=', ServiceType::Consultation)->where('hcp_id', auth()->user()->id)->get();
                break;
        }
        $consultations = $consultations->sortByDesc('created_at');
        return view('consultations.index', compact('consultations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $workspaceId = auth()->user()->workspace_id;
        $providers = User::where('workspace_id', $workspaceId)->where('role', 1)->get();
        $users = User::where('workspace_id', $workspaceId)->whereIn('role', [1, 4])->get();
        $services = Type::where('type', '!=', 0)->get();

        return view('consultations.create', compact('providers', 'users', 'services'));
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
            'provider' => 'required|exists:users,id',
            'schedule' => 'required|date',
            'user_id' => 'nullable|exists:users,id',
            'service_id' => 'required|exists:types,id'
        ]);
        
        $type = Type::findOrFail($validated['service_id']);

        if(!$request->has('user_id')) {
            $validated['user_id'] = auth()->user()->id;
        }
        $user = User::findOrFail($validated['user_id'], ['workspace_id']);
        Consultation::create([
            'user_id' => $validated['user_id'],
            'hcp_id' => $validated['provider'],
            'starts_at' => Carbon::parse($validated['schedule']),
            'ends_at' => Carbon::parse($validated['schedule'])->addMinutes(30),
            'service_type' => $type->type->value,
            'service_id' => $validated['service_id'],
            'workspace_id' => $user->workspace_id,
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
        $consultation = Consultation::findOrFail($id);
        
        if($consultation->service_type == ServiceType::Consultation()) {
            abort(401);
        }

        $user = auth()->user();
        if($user->role != UserRole::Admin()) {
            if($user->role == UserRole::HCP()) {
                if ($consultation->hcp_id != $user->id) {
                    return abort(401);
                }
            }
            if($user->role == UserRole::Employee()) {
                if ($consultation->user_id != $user->id) {
                    return abort(401);
                }
            }
        }

        $forms = Form::where('owner_id', auth()->user()->id)->get();
        return view('consultations.show', compact('consultation', 'forms'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateForms(Request $request, int $serviceId)
    {
        $service = Consultation::findOrFail($serviceId);

        $data = $this->validate($request, [
            'user-forms' => 'nullable',
            'user-forms.*' => 'required|exists:forms,id',
            'hcp-forms' => 'nullable',
            'hcp-forms.*' => 'required|exists:forms,id',
        ]);

        if($request->has('user-forms')) {
            foreach ($data['user-forms'] as $key => $value) {
                ConsultationForm::firstOrCreate([
                    'consultation_id' => $service->id,
                    'answerable_by' => $service->user->id,
                    'form_id' => $value
                ]);
            }

            DB::table('consultation_form')->where('consultation_id', $service->id)->where('answerable_by', $service->user->id)->whereNotIn('form_id', $data['user-forms'])->delete();
        } else {
            DB::table('consultation_form')->where('consultation_id', $service->id)->where('answerable_by', $service->user->id)->delete();
        }

        if($request->has('hcp-forms')) {
            foreach ($data['hcp-forms'] as $key => $value) {
                ConsultationForm::firstOrCreate([
                    'consultation_id' => $service->id,
                    'answerable_by' => $service->provider->id,
                    'form_id' => $value
                ]);
            }

            DB::table('consultation_form')->where('consultation_id', $service->id)->where('answerable_by', $service->provider->id)->whereNotIn('form_id', $data['hcp-forms'])->delete();
        } else {
            DB::table('consultation_form')->where('consultation_id', $service->id)->where('answerable_by', $service->provider->id)->delete();
        }

        return redirect()->back();
    }
}

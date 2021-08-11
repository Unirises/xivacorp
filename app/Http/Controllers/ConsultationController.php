<?php

namespace App\Http\Controllers;

use App\Enums\ServiceType;
use App\Enums\UserRole;
use App\Models\Consultation;
use App\Models\Form;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConsultationController extends Controller
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
                $consultations = Consultation::where('service_type', ServiceType::Consultation)->get();
                break;
            case UserRole::CoAdmin():
            case UserRole::Clinic():
            case UserRole::HR():
                $consultations = Consultation::where('service_type', ServiceType::Consultation)->where('workspace_id', auth()->user()->workspace_id)->get();
                break;
            case UserRole::Employee():
                $consultations = Consultation::where('service_type', ServiceType::Consultation)->where('user_id', auth()->user()->id)->get();
                break;
            case UserRole::HCP():
                $consultations = Consultation::where('service_type', ServiceType::Consultation)->where('hcp_id', auth()->user()->id)->get();
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

        return view('consultations.create', compact('providers', 'users'));
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
        ]);
        
        if(!$request->has('user_id')) {
            $validated['user_id'] = auth()->user()->id;
        }

        $user = User::findOrFail($validated['user_id'], ['workspace_id']);

        $startsAt = Carbon::parse($validated['schedule']);
        $endsAt = Carbon::parse($validated['schedule'])->addMinutes(30);

        $response = Http::withToken(env('DAILY_API', 'be86164a3699b823d22e5cc4d7ae84919e941b61f8226c3fdd6740934922d43e'))->post('https://api.daily.co/v1/rooms', [
            'properties' => [
                'exp' => $endsAt->timestamp,
                // 'nbf' => $startsAt->timestamp,
                'max_participants' => 2,
                'enable_chat' => true,
                'enable_screenshare' => true,
            ]
        ]);

        Consultation::create([
            'user_id' => $validated['user_id'],
            'hcp_id' => $validated['provider'],
            'starts_at' => Carbon::parse($validated['schedule']),
            'ends_at' => Carbon::parse($validated['schedule'])->addMinutes(30),
            'room_id' => $response->json()['url'],
            'service_type' => ServiceType::Consultation,
            'workspace_id' => $user->workspace_id,
        ]);

        return redirect()->route('consultations.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function show(Consultation $consultation)
    {
        if($consultation->service_type != ServiceType::Consultation()) {
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
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function edit(Consultation $consultation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consultation $consultation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Consultation $consultation)
    {
        //
    }

    public function export()
    {
        switch(auth()->user()->role) {
            case UserRole::Admin():
                $consultations = Consultation::where('service_type', ServiceType::Consultation)->get();
                break;
            case UserRole::CoAdmin():
            case UserRole::Clinic():
            case UserRole::HR():
                $consultations = Consultation::where('service_type', ServiceType::Consultation)->where('workspace_id', auth()->user()->workspace_id)->get();
                break;
            case UserRole::Employee():
            case UserRole::HCP():
                $consultations = Consultation::where('service_type', ServiceType::Consultation)->where('user_id', auth()->user()->id)->orWhere('hcp_id', auth()->user()->id)->get();
                break;
        }
        $consultations = $consultations->sortByDesc('created_at');
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=consultations.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Workspace ID', 'Client', 'Provider', 'Starts At', 'Ends At');

        $callback = function() use($consultations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($consultations as $consultation) {
                $row['Workspace ID'] = $consultation->workspace_id;
                $row['Client'] = $consultation->user->name;
                $row['Provider'] = $consultation->provider->name;
                $row['Starts At'] = $consultation->starts_at;
                $row['Ends At'] = $consultation->ends_at;

                fputcsv($file, array($row['Workspace ID'], $row['Client'], $row['Provider'], $row['Starts At'], $row['Ends At']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

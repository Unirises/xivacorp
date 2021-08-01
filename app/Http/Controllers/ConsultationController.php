<?php

namespace App\Http\Controllers;

use App\Enums\ServiceType;
use App\Enums\UserRole;
use App\Models\Consultation;
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
        $isAdmin = auth()->user()->role == UserRole::Admin();
        $consultations = $isAdmin ? Consultation::where('service_type', ServiceType::Consultation)->get() : Consultation::where('service_type', ServiceType::Consultation)->where('user_id', auth()->user()->id)->orWhere('hcp_id', auth()->user()->id)->get();
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

        $startsAt = Carbon::parse($validated['schedule']);
        $endsAt = Carbon::parse($validated['schedule'])->addMinutes(30);

        $response = Http::withToken(env('DAILY_API', 'be86164a3699b823d22e5cc4d7ae84919e941b61f8226c3fdd6740934922d43e'))->post('https://api.daily.co/v1/rooms', [
            'properties' => [
                'exp' => $endsAt->timestamp,
                'nbf' => $startsAt->timestamp,
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
        return view('consultations.show', compact('consultation'));
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
}

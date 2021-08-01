<?php

namespace App\Http\Controllers;

use App\Enums\ServiceType;
use App\Enums\TypeIdent;
use App\Enums\UserRole;
use App\Models\Consultation;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isAdmin = auth()->user()->role == UserRole::Admin();
        $consultations = $isAdmin ? Consultation::where('service_type', '!=', ServiceType::Consultation)->get() : Consultation::where('service_type', '!=', ServiceType::Consultation)->where('user_id', auth()->user()->id)->orWhere('hcp_id', auth()->user()->id)->get();
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

        Consultation::create([
            'user_id' => $validated['user_id'],
            'hcp_id' => $validated['provider'],
            'starts_at' => Carbon::parse($validated['schedule']),
            'ends_at' => Carbon::parse($validated['schedule'])->addMinutes(30),
            'service_type' => $type->type->value,
            'service_id' => $validated['service_id'],
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
        return view('consultations.show', compact('consultation'));
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
}

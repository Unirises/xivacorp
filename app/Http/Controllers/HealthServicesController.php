<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HealthServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('services.index');
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
        return view('services.show');
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
}

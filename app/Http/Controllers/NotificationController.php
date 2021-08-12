<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\HcpData;
use App\Models\User;
use App\Models\WorkingHoursNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->role->value != 0, 401);

        $requests = WorkingHoursNotification::all();

        return view('admin.working-hours-notification', compact('requests'));
    }

    public function approve(int $id)
    {
        abort_if(auth()->user()->role->value != 0, 401);

        $data = WorkingHoursNotification::findOrFail($id);
    
        User::where('id', $data->user_id)->update([
            'hours' => $data->hours
        ]);

        WorkingHoursNotification::where('user_id', $data->user_id)->delete();

        return redirect()->back();
    }

    public function companyIndex()
    {
        abort_if(auth()->user()->role->value != 0, 401);

        $hcps = User::where('role', 1)->get();
        $companies = Company::all();

        return view('admin.change-company', compact('hcps', 'companies'));
    }

    public function companyChange(Request $request, int $id)
    {
        abort_if(auth()->user()->role->value != 0, 401);
        $request->validate([
            'code' => 'required|exists:companies,code'
        ]);
        User::where('id', $id)->update([
            'workspace_id' => $request->code,
        ]);

        return redirect()->back();
    }
}

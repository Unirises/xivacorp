<?php

namespace App\Http\Controllers;

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
}

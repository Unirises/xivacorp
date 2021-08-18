<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Service;
use App\Models\ServiceForms;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if(auth()->user()->role->value == 4) {
            return view('profile.edit');
        }
        switch(auth()->user()->role) {
            case UserRole::Admin():
                $companies = Company::all();
                break;
            case UserRole::CoAdmin():
            case UserRole::Clinic():
            case UserRole::HR():
            case UserRole::HCP():
                $companies = Company::where('code', auth()->user()->workspace_id)->get();
                break;
        }
        $lineChart = 
            Service::where('workspace_id', auth()->user()->workspace_id)->get()->groupBy(function($model) {
                return Carbon::parse($model->schedule)->format('d');
            });

        $customArray = [];

        foreach($lineChart as $day => $services) {
            array_merge($customArray, array($day => []));
            foreach($services as $index => $service) {
                $recurring = ServiceForms::where('service_id', $service->id)->where('is_exportable', true)->count();
                $customArray[$day][$service->service->meta][] = $service;
                $customArray[$day][$service->service->meta][] = $recurring;
            }
        }

        $canView = true;

        if (auth()->user()->role == UserRole::HCP()) {
            if (auth()->user()->in_schedule && (auth()->user()->hcp_data->signature ?? null) != null) {
            } else {
                $canView = false;
            }
        }

        
        return view('dashboard', compact('companies', 'customArray', 'canView'));
    }
}

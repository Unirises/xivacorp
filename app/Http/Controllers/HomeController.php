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
        $recurringChart = 
            ServiceForms::where('is_exportable', true)->whereNotNull('answer')->whereHas('service', function($q) {
                $q->where('workspace_id', auth()->user()->workspace_id);
            })->where('created_at', '>=', Carbon::now()->subDays(10))->get()->groupBy(function($model) {
                return Carbon::parse($model->created_at)->format('m/d/Y');
            });
            
        $customArray = [];

        foreach($recurringChart as $day => $services) {
            array_merge($customArray, array($day => []));
            foreach($services as $index => $service) {
                $customArray[$day][$service->service->service->meta][] = $service;
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

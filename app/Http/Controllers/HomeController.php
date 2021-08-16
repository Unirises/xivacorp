<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Company;

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
       
        return view('dashboard', compact('companies'));
    }
}

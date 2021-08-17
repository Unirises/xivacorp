<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\HcpData;
use App\Models\WorkingHoursNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\OpeningHours\OpeningHours;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $employee = auth()->user();
        return view('profile.edit', compact('employee'));
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        if (auth()->user()->id == 1) {
            return back()->withErrors(['not_allow_profile' => __('You are not allowed to change data for a default user.')]);
        }

        auth()->user()->update($request->all());

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        if (auth()->user()->id == 1) {
            return back()->withErrors(['not_allow_password' => __('You are not allowed to change the password for a default user.')]);
        }

        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }

    public function hours(Request $request)
    {
        $validated = $this->validate($request, [
            'days' => 'required|array',
            'days.*' => ['required', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'start' => 'required|date_format:H:i',
            'end' => 'required|after_or_equal:start|date_format:H:i'
        ]);

        $formattedDays = [];
        foreach ($validated['days'] as $key => $value) {
            $formattedDays[$value] = [$validated['start'].'-'.$validated['end']];
        }

        WorkingHoursNotification::create([
            'user_id' => auth()->user()->id,
            'hours' => json_encode($formattedDays)
        ]);
        
        return redirect()->back()->with('success', 'Your new working hours is now for approval from admin.');
    }

    public function signature(Request $request)
    {
        $validated =  $this->validate($request, [
            'signature' => 'required|string',
        ]);

        HcpData::where('user_id', auth()->user()->id)->update([
            'signature' => $validated['signature']
        ]);

        return redirect()->back();
    }

    public function email(Request $request)
    {
        $data = $this->validate($request, [
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,'.auth()->user()->id],
        ]);

        auth()->user()->update([
            'email' => $data['email']
        ]);

        return redirect()->back();
    }

    public function info(Request $request)
    {
        $validated = $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'dob' => 'required|date',
            'gender' => 'required|numeric',
        ]);

        auth()->user()->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'middle_name' => $validated['middle_name'],
            'street_address' => $validated['street_address'],
            'city' => $validated['city'],
            'barangay' => $validated['barangay'],
            'region' => $validated['region'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
        ]);

        return redirect()->back();
    }
}

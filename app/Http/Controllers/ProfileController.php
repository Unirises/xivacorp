<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Http\Request;
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
        return view('profile.edit');
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
        
        $user = auth()->user();
        $user->hours = json_encode($formattedDays);
        $user->save();
        
        return redirect()->back();
    }
}

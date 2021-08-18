<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Models\HcpData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('users.index');
    }

    public function onboard(Request $request)
    {
        $array = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'type' => 'required|digits_between:1,4',
            'code' => 'nullable|exists:companies,code',
            'gender' => 'required|numeric',
            'mobile_number' => 'required',
            'telephone_number' => 'nullable',
        ];

        if($request['type'] == '1') {
            $array = array_merge($array, [
                'role' => 'required|exists:types,id',
                'prc_id' => 'required',
                'selfie' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'signature' => 'required|string',
            ]);
        }

        $validated = $this->validate($request, $array, [], [
            'selfie' => 'PRC ID',
        ]);

        User::where('id', auth()->user()->id)->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'middle_name' => $validated['middle_name'],
            'street_address' => $validated['street_address'],
            'city' => $validated['city'],
            'barangay' => $validated['barangay'],
            'region' => $validated['region'],
            'role' => $validated['type'],
            'workspace_id' => $validated['code'],
            'gender' => $validated['gender'],
            'is_onboarded' => true,
            'mobile_number' => $validated['mobile_number'],
            'telephone_number' => $validated['telephone_number'],
        ]);


        if($validated['type'] == '1') {
            $filename =  Str::random(22) . '.' . 'png';
            Storage::disk('local')->put('public/hcp/' . $filename, File::get($request->file('selfie')));

            HcpData::create([
                'user_id' => auth()->user()->id,
                'type_id' => $validated['role'],
                'prc_id' => $validated['prc_id'],
                'photo' => $filename,
                'signature' => $validated['signature'],
            ]);
        }

        return redirect()->route('home');
    }
}

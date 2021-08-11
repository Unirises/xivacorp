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
            'type' => 'required|digits_between:1,4',
            'code' => 'required|exists:companies,code',
            'address' => 'required|string',
            'gender' => 'required|numeric',
        ];

        if($request['type'] == '1') {
            $array = array_merge($array, [
                'role' => 'required|exists:types,id',
                'prc_id' => 'required',
                'selfie' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096'
            ]);
        }

        $validated = $this->validate($request, $array);

        User::where('id', auth()->user()->id)->update([
            'role' => $validated['type'],
            'workspace_id' => $validated['code'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'is_onboarded' => true,
        ]);


        if($validated['type'] == '1') {
            $filename =  Str::random(22) . '.' . 'png';
            Storage::disk('local')->put('public/hcp/' . $filename, File::get($request->file('selfie')));

            HcpData::create([
                'user_id' => auth()->user()->id,
                'type_id' => $validated['role'],
                'prc_id' => $validated['prc_id'],
                'photo' => $filename,
            ]);
        }

        return redirect()->route('home');
    }
}

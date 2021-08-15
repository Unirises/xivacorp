<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\HcpData;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $array = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'street_address' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => 'required|digits_between:1,4',
            'code' => 'nullable|exists:companies,code',
            'dob' => 'required|date',
            'gender' => 'required|numeric',
        ];
        
        if($data['type'] == '1') {
            $array = array_merge($array, [
                'role' => 'required|exists:types,id',
                'prc_id' => 'required',
                'selfie' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'signature' => 'required|string',
            ]);
        }

        return Validator::make($data, $array, [], [
            'selfie' => 'PRC ID',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create($data)
    {
        $data = request()->all();
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'street_address' => $data['street_address'],
            'barangay' => $data['barangay'],
            'region' => $data['region'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['type'],
            'workspace_id' => $data['code'],
            'dob' => $data['dob'],
            'gender' => $data['gender'],
        ]);

        if($data['type'] == '1') {
            $filename =  Str::random(22) . '.' . 'png';
            Storage::disk('local')->put('public/hcp/' . $filename, File::get(request()->file('selfie')));

            HcpData::create([
                'user_id' => $user->id,
                'type_id' => $data['role'],
                'prc_id' => $data['prc_id'],
                'photo' => $filename,
                'signature' => $data['signature'],
            ]);
        }

        return $user;
    }
}

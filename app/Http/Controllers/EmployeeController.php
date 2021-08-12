<?php

namespace App\Http\Controllers;

use App\Enums\TypeIdent;
use App\Enums\UserRole;
use App\Models\HcpData;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $employees = $user->role == UserRole::Admin() ? User::all() : User::where('workspace_id', $user->workspace_id)->get();
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::where('type', TypeIdent::HCP)->get();
        return view('employees.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $array = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => 'required|digits_between:1,4',
            'code' => 'nullable|exists:companies,code',
            'dob' => 'required|date',
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

        $validated = $this->validate($request, $array, [], [
            'selfie' => 'PRC ID',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['type'],
            'workspace_id' => $validated['code'],
            'dob' => $validated['dob'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
        ]);

        if($validated['type'] == '1') {
            $filename =  Str::random(22) . '.' . 'png';
            Storage::disk('local')->put('public/hcp/' . $filename, File::get($request->file('selfie')));

            HcpData::create([
                'user_id' => $user->id,
                'type_id' => $validated['role'],
                'prc_id' => $validated['prc_id'],
                'photo' => $filename,
            ]);
        }

        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $employee = User::findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $employee = User::findOrFail($id);
        $types = Type::where('type', TypeIdent::HCP)->get();
        return view('employees.edit', compact('employee', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $array = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'type' => 'required|digits_between:1,4',
            'code' => 'nullable|exists:companies,code',
            'dob' => 'required|date',
            'address' => 'required|string',
            'gender' => 'required|numeric',
        ];

        if($request['type'] == '1') {
            $array = array_merge($array, [
                'role' => 'required|exists:types,id',
                'prc_id' => 'required',
            ]);
        } 

        $validated = $this->validate($request, $array);
        $validated['password'] = $request->filled('password') ?  Hash::make($validated['password']) : $user->password;

        if($request['type'] != '1') {
            HcpData::where('user_id', $user->id)->delete();
        } 

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['type'],
            'workspace_id' => $validated['code'],
            'dob' => $validated['dob'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
        ]);

        if($user->hcp_data && $request['type'] == '1') {
            $user->hcp_data->update([
                'type_id' => $validated['role'],
                'prc_id' => $validated['prc_id']
            ]);
        } else if ($request['type'] == '1' && !$user->hcp_data) {
            HcpData::create([
                'user_id' => $user->id,
                'type_id' => $validated['role'],
                'prc_id' => $validated['prc_id'],
                'photo' => 'default.png',
            ]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back();
    }
}

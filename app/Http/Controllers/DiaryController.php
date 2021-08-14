<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $consultationId)
    {
        $diaries = Diary::where('consultation_id', $consultationId)->get();
        return view('diary.index', compact('diaries', 'consultationId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(int $consultationId)
    {
        $consultation = Service::findOrFail($consultationId);
        $users = User::whereIn('id', [$consultation->user_id, $consultation->hcp_id])->get();
        return view('diary.create', compact('consultationId', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $consultationId)
    {
        $data = $this->validate($request, [
            'note' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'photo' => 'nullable|image',
        ]);

        if (!$request->has('user_id')) {
            $data['user_id'] = auth()->user()->id;
        }

        if($request->hasFile('photo')) {
            $filename =  Str::random(22) . '.' . 'png';
            Storage::disk('local')->put('public/diaries/' . $filename, File::get($request->file('photo')));
            $validated['photo'] = $filename;
        }

        Diary::create([
            'note' => $data['note'],
            'user_id' => $data['user_id'],
            'consultation_id' => $consultationId,
            'photo' => $request->hasFile('photo') ? $validated['photo'] : null
        ]);

        return redirect()->route('services.diary.index', $consultationId);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Http\Response
     */
    public function show(Diary $diary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Http\Response
     */
    public function edit(int $consultationId, int $diaryId)
    {
        $consultation = Service::findOrFail($consultationId);
        $users = User::whereIn('id', [$consultation->user_id, $consultation->hcp_id])->get();

        return view('diary.edit', compact('users', 'consultationId', 'diaryId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $consultationId, int $diaryId)
    {
        $data = $this->validate($request, [
            'note' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'photo' => 'nullable|image',
        ]);

        if (!$request->has('user_id')) {
            $data['user_id'] = auth()->user()->id;
        }

        $toUpdate = [
            'note' => $data['note'],
            'user_id' => $data['user_id'],
        ];

        if($request->hasFile('photo')) {
            $filename =  Str::random(22) . '.' . 'png';
            Storage::disk('local')->put('public/diaries/' . $filename, File::get($request->file('photo')));
            $data['photo'] = $filename;
            $toUpdate['photo'] = $data['photo'];
        }
        
        Diary::where('id', $diaryId)->update($toUpdate);

        return redirect()->route('services.diary.index', $consultationId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diary $diary)
    {
        //
    }
}

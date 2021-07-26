<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Answer;
use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = Form::all();
        return view('forms.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('forms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated =  $this->validate($request, ['form_name' => 'required|string', 'data' => 'required|json', 'required' => 'nullable']);

        Form::create([
            'name' => $validated['form_name'],
            'data' => json_encode($validated['data']),
            'required' => $request->has('required') ? true : false,
        ]);

        return redirect()->back();
    }

    public function storeAnswer(Request $request)
    {
        $validated =  $this->validate($request, [
            'data' => 'required|array',
            'data.*.name' => 'nullable|string',
            'data.*.label' => 'nullable|string',
            'data.*.value' => 'nullable|string',
            'form_id' => 'required|exists:forms,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if(!$request->filled('user_id')) {
            $validated['user_id'] = auth()->user()->id;
        }

        Answer::updateOrCreate([
            'data' => json_encode($validated['data']),
            'form_id' => $validated['form_id'],
            'user_id' => $validated['user_id']
        ]);
        
        return route('view-answer', ['formId' => $validated['form_id'], 'userId' => $validated['user_id']]);
    }

    public function showAnswers(int $formId, int $userId)
    {
        $answer = Answer::where('form_id', $formId)->where('user_id', $userId)->firstOrFail();
        $answer->data = json_decode($answer['data']);
        return view('forms.answer', compact('answer'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        $form['data'] = json_decode($form['data']);
        $users = null;

        if(auth()->user()->role == UserRole::Admin()) {
            $users = User::all();
        } else if(auth()->user()->role == UserRole::HCP()) {
            $users = User::where('workspace_id', auth()->user()->workspace_id)->get();
        }

        return view('forms.show', compact('form', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
    {
        $form->delete();

        return redirect()->back();
    }
}

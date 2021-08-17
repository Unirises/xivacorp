<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $items = $user->role == UserRole::Admin() ? Item::all() : Item::where('viewable_as', $user->role->value)->get();
        return view('marketplace.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('marketplace.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image',
            'viewable_as' => 'required|digits_between:1,4',
            'price' => 'nullable|numeric',
        ]);

        if($request->hasFile('photo')) {
            $filename =  Str::random(22) . '.' . 'png';
            Storage::disk('local')->put('public/items/' . $filename, File::get($request->file('photo')));
            $validated['photo'] = $filename;
        }

        Item::create($validated);

        return redirect()->route('marketplace.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $item = Item::findOrFail($id);

        return view('marketplace.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validated = $this->validate($request, [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image',
            'viewable_as' => 'required|digits_between:1,4',
            'price' => 'nullable|numeric',
        ]);

        if($request->hasFile('photo')) {
            $filename =  Str::random(22) . '.' . 'png';
            Storage::disk('local')->put('public/items/' . $filename, File::get($request->file('photo')));
            $validated['photo'] = $filename;
        } else {
            unset($validated['photo']);
        }

        Item::where('id', $id)->update($validated);

        return redirect()->route('marketplace.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $item = Item::findOrFail($id);

        $item->delete();

        return redirect()->back();
    }
}

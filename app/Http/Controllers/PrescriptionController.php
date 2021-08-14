<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(int $id)
    {
        return view('prescriptions.create')->with('consultation_id', $id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $id, Request $request)
    {
        $data = $this->validate($request, [
            'referral' => 'nullable|string',
            'prescription' => 'required|string',
        ]);

        Prescription::create([
            'referral' => array_key_exists('referral', $data) ? $data['referral'] : null,
            'prescription' => $data['prescription'],
            'consultation_id' => $id,
            'hcp_id' => auth()->user()->id,
        ]);

        return redirect()->route('services.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function show(int $cId, int $pId)
    {
        $prescription = Prescription::findOrFail($pId);

        $img = Image::make(public_path('px-template.jpg'));
        $img->text($prescription->consultation->client->name, 550, 670, function ($font) {
            $font->file(public_path('helvetica.otf'));
            $font->size(28);
        });
        $img->text(Carbon::parse($prescription->consultation->client->dob)->isoFormat('MMMM Do YYYY'), 550, 705, function ($font) {
            $font->file(public_path('helvetica.otf'));
            $font->size(28);
        });
        $img->text(Carbon::parse($prescription->consultation->client->dob)->age, 1175, 705, function ($font) {
            $font->file(public_path('helvetica.otf'));
            $font->size(28);
        });
        $img->text($prescription->consultation->client->address, 550, 740, function ($font) {
            $font->file(public_path('helvetica.otf'));
            $font->size(28);
        });
        $img->text($prescription->consultation->client->gender->description, 1175, 740, function ($font) {
            $font->file(public_path('helvetica.otf'));
            $font->size(28);
        });
        $img->text($prescription->provider->name, 1075, 1560, function ($font) {
            $font->file(public_path('helvetica.otf'));
            $font->size(28);
        });
        $img->text($prescription->provider->hcp_data->prc_id ?? '/ ADMIN /', 1075, 1560 + 35, function ($font) {
            $font->file(public_path('helvetica.otf'));
            $font->size(28);
        });

        $lines = explode("\n", wordwrap($prescription->prescription, 50));

        for ($i = 0; $i < count($lines); $i++) {
            $offset = 820 + ($i * 50); // 50 is line height
            $img->text($lines[$i], 550, $offset, function ($font) {
                $font->file(public_path('helvetica.otf'));
                $font->size(28);
            });
        }

        if($prescription->referral) {
            $img->text('
            - - - - - - - - - - - - - - -
            For Referral To: ' . $prescription->referral . '
            - - - - - - - - - - - - - - -
            ', 300, 1500 + 35, function ($font) {
                $font->file(public_path('helvetica.otf'));
                $font->size(20);
            });
        }
        return $img->response('jpg');
        return redirect()->route('services.show', $cId);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function edit(int $cId, int $pId)
    {
        return view('prescriptions.edit')->with('prescription', Prescription::findOrFail($pId))->with('consultationId', $cId)->with('prescriptionId', $pId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $cId, int $pId)
    {
        $data = $this->validate($request, [
            'referral' => 'nullable|string',
            'prescription' => 'required|string',
        ]);

        Prescription::where('consultation_id', $cId)->where('id', $pId)->update([
            'referral' => array_key_exists('referral', $data) ? $data['referral'] : null,
            'prescription' => $data['prescription'],
            'consultation_id' => $cId
        ]);

        return redirect()->route('services.show', $cId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prescription $prescription)
    {
        //
    }
}

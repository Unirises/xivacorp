<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription',
        'referral',
        'consultation_id'
    ];

    public function consultation() {
        return $this->belongsTo(Consultation::class);
    }
}

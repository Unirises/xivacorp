<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationForm extends Model
{
    use HasFactory;

    protected $table = 'consultation_form';
    public $timestamps = false;
    protected $fillable = [
        'consultation_id',
        'form_id',
        'required',
        'answerable_by',
        'signature',
        'photo',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'answerable_by', 'id');
    }

    public function getPhotoAttribute($value) {
        return $value == null ? null : url('storage/results/'.$value);
    }
}

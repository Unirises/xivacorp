<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'consultation_id',
        'note',
        'photo',
    ];

    public function consultation() {
        return $this->belongsTo(Consultation::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getPhotoAttribute($value)
    {
        return $value == null ? null : url('storage/diaries/'.$value);
    }
}

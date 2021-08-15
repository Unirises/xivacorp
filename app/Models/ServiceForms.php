<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceForms extends Model
{
    use HasFactory;

    protected $table = 'services_forms';

    protected $fillable = [
        'service_id',
        'form_id',
        'answerable_by',
        'answer',
        'photo',
        'doctor_name',
        'doctor_prc',
        'is_exportable',
        'signature',
        'need_signature',
    ];

    protected $appends = [
        'downloadable_photo_url',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function answerer()
    {
        return $this->belongsTo(User::class, 'answerable_by', 'id');
    }

    public function getDownloadablePhotoUrlAttribute()
    {
        $this->setAppends([]);
        $photo = substr($this->photo, strrpos($this->photo, '/') + 1);
        return public_path('storage/results/'.$photo);
    }

    public function getPhotoAttribute($value)
    {
        return $value == null ? null : url('storage/results/'.$value);
    }
}

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

    public function getPhotoAttribute($value)
    {
        return $value == null ? null : url('storage/results/'.$value);
    }
}

<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hcp_id',
        'starts_at',
        'ends_at',
        'room_id'
    ];

    protected $appends = [
        'forms'
    ];

    public function getFormsAttribute()
    {
        $userRole = auth()->user()->role;
        
        return $userRole == UserRole::Employee() ? $this->userForms() : $this->hcpForms();
    }

    public function userForms() {
        return $this->belongsToMany(Form::class, 'consultation_form', 'consultation_id', 'form_id')->withPivot('required', 'answerable_by');
    }

    public function hcpForms() {
        return $this->belongsToMany(Form::class, 'consultation_form', 'consultation_id', 'form_id')->withPivot('required', 'answerable_by');
    }
}

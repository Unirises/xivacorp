<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'data', 'required', 'owner_id'];

    protected $appends = ['has_answer'];

    public function getHasAnswerAttribute()
    {
        return Answer::where('user_id', auth()->user()->id)->where('form_id', $this->id)->exists();
    }

    public function service_forms()
    {
        return $this->hasMany(ServiceForms::class, 'form_id', 'id');
    }

    public function consultation_forms()
    {
        return $this->hasMany(ConsultationForm::class, 'form_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'form_id', 'id');
    }
}

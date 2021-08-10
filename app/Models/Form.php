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
}

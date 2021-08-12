<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHoursNotification extends Model
{
    use HasFactory;

    protected $table = 'working_hours_notification';

    protected $fillable = [
        'user_id',
        'hours'
    ];

    public function hcp()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

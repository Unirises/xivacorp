<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hcp_id',
        'workspace_id',
        'service_id',
        'schedule',
        'pending',
    ];

    protected $casts = [
        'schedule' => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'hcp_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Type::class, 'service_id', 'id');
    }
}

<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeCompany extends Model
{
    protected $table = 'company_change';

    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'workspace_id'
    ];

    protected $casts = [
        'role' => UserRole::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

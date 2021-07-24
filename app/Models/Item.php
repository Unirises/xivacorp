<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'photo',
        'viewable_as',
        'price',
    ];

    protected $casts = [
        'viewable_as' => UserRole::class,
    ];

    public function getPhotoAttribute($value) {
        return $value != null ? url('storage/items/' . rawurlencode($value)) : null;
    }
}

<?php

namespace App\Models;

use App\Enums\TypeIdent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
    ];

    protected $appends = [
        'meta',
    ];

    protected $casts = [
        'type' => TypeIdent::class,
    ];

    public function getMetaAttribute()
    {
        return $this->type->description . ' – ' . $this->name;
    }
}

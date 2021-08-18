<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'name', 'employer', 'contact'];

    protected $appends = [
        'statistics',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'workspace_id', 'code');
    }

    public function getStatisticsAttribute()
    {
        $recurringChart =
            ServiceForms::where('is_exportable', true)->whereNotNull('answer')->whereHas('service', function($q) {
                $q->where('workspace_id', $this->code);
            })->get();

        $customArray = [];

        foreach ($recurringChart as $service) {
            $customArray[$service->service->service->meta][] = $service;
        }

        return $customArray;
    }
}

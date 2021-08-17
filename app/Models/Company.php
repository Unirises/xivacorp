<?php

namespace App\Models;

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
        $types = Type::where('type', '!=', 0)->get();
        foreach ($types as $index => $type) {
            $serviced = Service::where('service_id', $type->id)->where('workspace_id', $this->code)->get();
            $type['serviced'] =  $serviced->count();
            if($serviced->count() < 1) {
                unset($types[$index]);
            }
        }
        return $types;
    }
}

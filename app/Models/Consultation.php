<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use App\Enums\ServiceType;
use App\Enums\UserRole;
use Carbon\Carbon;
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
        'room_id',
        'service_type',
        'service_id',
        'workspace_id',
    ];

    protected $appends = [
        'forms',
        'status',
        'status_color',
        'is_ongoing',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'service_type' => ServiceType::class,
    ];

    public function getStatusAttribute() {
        if($this->ends_at->isPast()) {
            return ServiceStatus::Completed();
        } else if(Carbon::now()->between($this->starts_at, $this->ends_at)) {
            return ServiceStatus::Ongoing();
        }

        return ServiceStatus::Upcoming();
    }

    public function getStatusColorAttribute() {
        if($this->ends_at->isPast()) {
            return 'success';
        } else if(Carbon::now()->between($this->starts_at, $this->ends_at)) {
            return 'warning';
        }

        return 'info';
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function provider() {
        return $this->belongsTo(User::class, 'hcp_id', 'id');
    }

    public function service() {
        return $this->belongsTo(Type::class);
    }

    public function prescription() {
        return $this->hasOne(Prescription::class);
    }

    public function diaries() {
        return $this->hasMany(Diary::class);
    }

    public function forms() {
        return $this->belongsToMany(Form::class, 'consultation_form', 'consultation_id', 'form_id')->withPivot('required', 'answerable_by');
    }

    public function getIsOngoingAttribute()
    {
        return Carbon::now()->between($this->starts_at, $this->ends_at);
    }
}

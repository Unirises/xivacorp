<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Enums\UserRole;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\OpeningHours\OpeningHours;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'hcp_data_id',
        'role',
        'workspace_id',
        'dob',
        'gender',
        'hours',
        'is_onboarded',
        'last_name',
        'first_name',
        'middle_name',
        'street_address',
        'barangay',
        'region',
        'city',
        'mobile_number',
        'telephone_number',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => UserRole::class,
        'gender' => GenderEnum::class,
    ];

    protected $appends = [
        'company_name',
        'in_schedule',
        'working_hours',
        'name',
        'address',
        'recent_service',
    ];

    public function hcp_data()
    {
        return $this->hasOne(HcpData::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'workspace_id', 'code');
    }

    public function getCompanyNameAttribute() {
        return $this->company->name ?? 'Floating';
    }

    public function getInScheduleAttribute()
    {
        if(!$this->hours ?? null) {
            return false;
        }

        return (OpeningHours::create(json_decode($this->hours, true)))->isOpen();
    }

    public function getWorkingHoursAttribute()
    {
        if(!$this->hours ?? null) {
            return false;
        }

        $decoded = json_decode($this->hours, true);
        $exploded = explode('-', reset($decoded)[0]);
        return [$exploded[0], $exploded[1]];
    }

    public function getNameAttribute()
    {
        return strtoupper(implode(", ", array_filter([$this->last_name, $this->first_name, $this->middle_name])));
    }
    
    public function getAddressAttribute()
    {
        return strtoupper(implode(", ", array_filter([$this->street_address, $this->barangay, $this->city, $this->region])));
    }

    public function getRecentServiceAttribute()
    {
        $service = Service::where('user_id', $this->id)->latest()->first();
        if(!$service) {
            return 'N/A';
        }
        
        return Carbon::parse($service->created_at)->format('m/d/Y g:i A');
    }
}

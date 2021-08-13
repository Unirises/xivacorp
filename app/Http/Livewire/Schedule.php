<?php

namespace App\Http\Livewire;

use App\Models\Consultation;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Livewire\Component;

class Schedule extends Component
{
    public $provider;
    public $date;


    public function render()
    {
        $workspaceId = auth()->user()->workspace_id;
        $providers = User::where('workspace_id', $workspaceId)->where('role', 1)->whereNotNull('hours')->with(['hcp_data' => function ($q) {
            $q->where('hcp_data.type_id', '=', 3);
        }])->get();
        return view('livewire.schedule', compact('providers'));
    }

    public function updatedProvider()
    {
        $user = User::find($this->provider);

        if(!$user) {
            return;
        }

        $days = json_decode($user->hours, true);

        $daysToBlock = [];

        if(!array_key_exists('sunday', $days)) {
            array_push($daysToBlock, 0);
        }

        if(!array_key_exists('monday', $days)) {
            array_push($daysToBlock, 1);
        }

        if(!array_key_exists('tuesday', $days)) {
            array_push($daysToBlock, 2);
        }

        if(!array_key_exists('wednesday', $days)) {
            array_push($daysToBlock, 3);
        }

        if(!array_key_exists('thursday', $days)) {
            array_push($daysToBlock, 4);
        }

        if(!array_key_exists('friday', $days)) {
            array_push($daysToBlock, 5);
        }

        if(!array_key_exists('saturday', $days)) {
            array_push($daysToBlock, 6);
        }

        $this->emit('updateDays', $daysToBlock);
    }

    public function updatedDate()
    {
        $provider = User::find($this->provider);

        if(!$provider) {
            return;
        }

        $parsedDate = Carbon::parse($this->date);
        $bookings = Consultation::select('starts_at', 'hcp_id')->where('hcp_id', $this->provider)->whereBetween('starts_at', [$parsedDate->copy()->startOfDay(), $parsedDate->copy()->endOfDay()])->get();
        $data = CarbonInterval::minutes(30)->toPeriod($this->date . ' ' . $provider->working_hours[0], $this->date.' '.$provider->working_hours[1])->toArray();
        
        foreach ($bookings as $booking) {
            foreach ($data as $key => $value) {
                if($booking->starts_at->toDateTimeString() == $value->toDateTimeString()) {
                    unset($data[$key]);
                }               
            }
        }

        $this->emit('updateHours', $data);
    }
}

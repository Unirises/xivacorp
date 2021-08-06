<?php

namespace App\Http\Livewire;

use App\Models\User;
use Carbon\CarbonInterval;
use Livewire\Component;

class Schedule extends Component
{
    public $provider;
    public $date;


    public function render()
    {
        $workspaceId = auth()->user()->workspace_id;
        $providers = User::where('workspace_id', $workspaceId)->where('role', 1)->whereNotNull('hours')->get();
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
        $this->emit('updateHours', CarbonInterval::minutes(30)->toPeriod($this->date . ' 2:00 PM', $this->date.' 11:59 PM')->toArray());
    }
}

<?php

namespace App\Livewire;

use App\Models\Member;
use Carbon\Carbon;
use Livewire\Component;

class DashboardMemberStats extends Component
{
    public $tab = 'entries';

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        $now = Carbon::now();

        return view('livewire.dashboard-member-stats', [
            'entries' => Member::whereMonth('entry_date', $now->month)->get(),
            'exits' => Member::whereMonth('exit_date', $now->month)->get(),
            'birthdays' => Member::whereMonth('birthday', $now->month)->get(),
            'anniversaries' => Member::whereMonth('entry_date', $now->month)->get()->filter(function ($member) use ($now) {
                return $member->entry_date->day === $now->day;
            }),
        ]);
    }
}

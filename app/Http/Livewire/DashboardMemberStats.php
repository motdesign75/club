<?php

namespace App\Http\Livewire;

use App\Models\Member;
use Carbon\Carbon;
use Livewire\Component;


class DashboardMemberStats extends Component
{
    public string $tab = 'entries';

    public function setTab(string $tab): void
    {
        // Debug-Log bei Tab-Wechsel
        logger()->info('Livewire-Tab-Klick:', ['tab' => $tab]);
        $this->tab = $tab;
    }

    public function getEntriesProperty()
    {
        return Member::whereMonth('entry_date', now()->month)->get();
    }

    public function getExitsProperty()
    {
        return Member::whereMonth('exit_date', now()->month)->get();
    }

    public function getBirthdaysProperty()
    {
        return Member::whereMonth('birthday', now()->month)->get();
    }

    public function getAnniversariesProperty()
    {
        $now = now();

        return Member::whereMonth('entry_date', $now->month)->get()->filter(function ($member) use ($now) {
            return $member->entry_date->day === $now->day;
        });
    }

    public function render()
    {
        return view('livewire.dashboard-member-stats');
    }
}

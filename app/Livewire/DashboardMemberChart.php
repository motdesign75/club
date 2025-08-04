<?php

namespace App\Livewire;

use App\Models\Member;
use Illuminate\Support\Carbon;
use livewire\Component;

class DashboardMemberChart extends Component
{
    public $months = [];
    public $entries = [];
    public $exits = [];
    public $totalMembers = [];

    public function mount()
    {
        $tenantId = auth()->user()->tenant_id;
        $year = now()->year;

        $this->months = collect(range(1, 12))->map(function ($m) {
            return Carbon::create(null, $m)->translatedFormat('F');
        })->toArray();

        $this->entries = [];
        $this->exits = [];
        $this->totalMembers = [];

        // Startdatum für Berechnung
        $startOfYear = Carbon::create($year, 1, 1);

        // Mitglieder, die bereits zum Jahresbeginn aktiv waren
        $runningTotal = Member::where('tenant_id', $tenantId)
            ->whereDate('entry_date', '<', $startOfYear)
            ->where(function ($query) use ($startOfYear) {
                $query->whereNull('exit_date')->orWhere('exit_date', '>=', $startOfYear);
            })
            ->count();

        foreach (range(1, 12) as $month) {
            $eintritte = Member::where('tenant_id', $tenantId)
                ->whereYear('entry_date', $year)
                ->whereMonth('entry_date', $month)
                ->count();

            $austritte = Member::where('tenant_id', $tenantId)
                ->whereYear('exit_date', $year)
                ->whereMonth('exit_date', $month)
                ->count();

            $runningTotal += $eintritte - $austritte;

            $this->entries[] = $eintritte;
            $this->exits[] = $austritte;
            $this->totalMembers[] = $runningTotal;
        }
    }

    public function render()
    {
        return view('livewire.dashboard-member-chart');
    }
}

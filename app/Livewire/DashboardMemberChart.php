<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Member;
use Carbon\Carbon;

class DashboardMemberChart extends Component
{
    public $months = [];
    public $entries = [];
    public $exits = [];
    public $totalMembers = [];

    public function mount()
    {
        $tenantId = auth()->user()->tenant_id;

        $start = now()->copy()->subMonths(11)->startOfMonth();
        $end = now()->copy()->endOfMonth();

        for ($i = 0; $i < 12; $i++) {

            $date = $start->copy()->addMonths($i);

            $monthStart = $date->copy()->startOfMonth();
            $monthEnd   = $date->copy()->endOfMonth();

            $this->months[] = $date->format('M y');


            // ✅ Eintritte nur in diesem Monat

            $entries = Member::where('tenant_id', $tenantId)
                ->whereNotNull('entry_date')
                ->whereBetween('entry_date', [$monthStart, $monthEnd])
                ->count();

            $this->entries[] = $entries;


            // ✅ Austritte nur in diesem Monat

            $exits = Member::where('tenant_id', $tenantId)
                ->whereNotNull('exit_date')
                ->whereBetween('exit_date', [$monthStart, $monthEnd])
                ->count();

            $this->exits[] = $exits;


            // ✅ Mitgliederbestand bis Monatsende

            $total = Member::where('tenant_id', $tenantId)
                ->whereNotNull('entry_date')
                ->whereDate('entry_date', '<=', $monthEnd)
                ->where(function ($q) use ($monthEnd) {
                    $q->whereNull('exit_date')
                      ->orWhere('exit_date', '>', $monthEnd);
                })
                ->count();

            $this->totalMembers[] = $total;
        }
    }

    public function render()
    {
        return view('livewire.dashboard-member-chart');
    }
}
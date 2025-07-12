<?php

namespace App\Livewire;

use App\Models\Member;
use Illuminate\Support\Carbon;
use Livewire\Component;

class DashboardMemberChart extends Component
{
    public $months = [];
    public $entries = [];
    public $exits = [];

    public function mount()
    {
        $tenantId = auth()->user()->tenant_id;
        $year = now()->year;

        $this->months = collect(range(1, 12))->map(function ($m) {
            return Carbon::create(null, $m)->translatedFormat('F');
        })->toArray();

        $this->entries = collect(range(1, 12))->map(function ($month) use ($tenantId, $year) {
            return Member::where('tenant_id', $tenantId)
                ->whereYear('entry_date', $year)
                ->whereMonth('entry_date', $month)
                ->count();
        })->toArray();

        $this->exits = collect(range(1, 12))->map(function ($month) use ($tenantId, $year) {
            return Member::where('tenant_id', $tenantId)
                ->whereYear('exit_date', $year)
                ->whereMonth('exit_date', $month)
                ->count();
        })->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard-member-chart');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Member;
use Carbon\Carbon;

class DashboardMemberStats extends Component
{
    public $entries = [];
    public $exits = [];
    public $birthdays = [];
    public $anniversaries = [];

    public function mount()
    {
        $tenantId = auth()->user()->tenant_id;

        $today = Carbon::today();


        /*
        =====================================
        Eintritte – letzte 30 Tage
        =====================================
        */

        $this->entries = Member::where('tenant_id', $tenantId)
            ->whereNotNull('entry_date')
            ->whereDate('entry_date', '>=', $today->copy()->subDays(30))
            ->orderBy('entry_date', 'desc')
            ->limit(5)
            ->get();


        /*
        =====================================
        Austritte – letzte 30 Tage
        =====================================
        */

        $this->exits = Member::where('tenant_id', $tenantId)
            ->whereNotNull('exit_date')
            ->whereDate('exit_date', '>=', $today->copy()->subDays(30))
            ->orderBy('exit_date', 'desc')
            ->limit(5)
            ->get();


        /*
        =====================================
        Geburtstage – nächste 14 Tage
        =====================================
        */

        $this->birthdays = Member::where('tenant_id', $tenantId)
            ->whereNotNull('birthday')
            ->get()
            ->filter(function ($m) use ($today) {

                $next = Carbon::parse($m->birthday)->year($today->year);

                if ($next->lt($today)) {
                    $next->addYear();
                }

                return $next->diffInDays($today) <= 14;
            })
            ->take(5);


        /*
        =====================================
        Jubiläen – dieses Jahr
        =====================================
        */

        $this->anniversaries = Member::where('tenant_id', $tenantId)
            ->whereNotNull('entry_date')
            ->get()
            ->filter(function ($m) use ($today) {

                $years = Carbon::parse($m->entry_date)->diffInYears($today);

                return $years > 0 && $years % 5 === 0;
            })
            ->take(5);
    }


    public function render()
    {
        return view('livewire.dashboard-member-stats');
    }
}
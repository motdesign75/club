<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Member;

class EventController extends Controller
{
    public function dashboardEvents()
    {
        $tenant = app('currentTenant');
        $tenantId = $tenant->id;

        // Kommende Events (max. 5)
        $events = Event::where('tenant_id', $tenantId)
            ->whereDate('start', '>=', now())
            ->orderBy('start')
            ->take(5)
            ->get();

        // Anzahl Mitglieder dieses Vereins
        $membersCount = Member::where('tenant_id', $tenantId)->count();

        // Lizenztyp (Fallback auf "Trial")
        $licenseType = $tenant->license_type ?? 'Trial';

        return view('dashboard', compact('events', 'tenant', 'membersCount', 'licenseType'));
    }

    public function index()
    {
        $events = Event::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('start', 'desc')
            ->get();

        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'start'       => 'required|date',
            'end'         => 'required|date|after_or_equal:start',
            'is_public'   => 'required|boolean',
        ]);

        Event::create([
            'tenant_id'   => Auth::user()->tenant_id,
            'title'       => $request->title,
            'description' => $request->description,
            'location'    => $request->location,
            'start'       => $request->start,
            'end'         => $request->end,
            'is_public'   => $request->is_public,
        ]);

        return redirect()->route('events.index')->with('success', 'Event wurde gespeichert.');
    }

    public function edit(Event $event)
    {
        $this->authorizeEvent($event);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'start'       => 'required|date',
            'end'         => 'required|date|after_or_equal:start',
            'is_public'   => 'required|boolean',
        ]);

        $event->update($request->all());

        return redirect()->route('events.index')->with('success', 'Event aktualisiert.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event gelöscht.');
    }

    private function authorizeEvent(Event $event)
    {
        if ($event->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProtocolMail;

class ProtocolController extends Controller
{
    public function index()
    {
        $protocols = Protocol::where('tenant_id', auth()->user()->tenant_id)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        return view('protocols.index', compact('protocols'));
    }

    public function create()
    {
        $members = Member::forCurrentTenant()->orderBy('last_name')->get();
        return view('protocols.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'type'            => 'required|string|max:255',
            'location'        => 'nullable|string|max:255',
            'start_time'      => 'nullable|date_format:H:i',
            'end_time'        => 'nullable|date_format:H:i',
            'content'         => 'required|string',
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'exists:members,id',
        ]);

        $protocol = Protocol::create([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id'   => Auth::id(),
            'title'     => $validated['title'],
            'type'      => $validated['type'],
            'location'  => $validated['location'] ?? null,
            'start_time'=> $validated['start_time'] ?? null,
            'end_time'  => $validated['end_time'] ?? null,
            'content'   => $validated['content'],
        ]);

        $protocol->participants()->sync($validated['participant_ids'] ?? []);

        return redirect()->route('protocols.index')->with('success', 'Protokoll erfolgreich gespeichert.');
    }

    public function show(Protocol $protocol)
    {
        if ($protocol->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return view('protocols.show', compact('protocol'));
    }

    public function edit(Protocol $protocol)
    {
        if ($protocol->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $members = Member::forCurrentTenant()->orderBy('last_name')->get();
        $selected = $protocol->participants->pluck('id')->toArray();

        return view('protocols.edit', compact('protocol', 'members', 'selected'));
    }

    public function update(Request $request, Protocol $protocol)
    {
        if ($protocol->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'type'            => 'required|string|max:255',
            'location'        => 'nullable|string|max:255',
            'start_time'      => 'nullable|date_format:H:i',
            'end_time'        => 'nullable|date_format:H:i',
            'content'         => 'required|string',
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'exists:members,id',
        ]);

        $protocol->update([
            'title'      => $validated['title'],
            'type'       => $validated['type'],
            'location'   => $validated['location'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'end_time'   => $validated['end_time'] ?? null,
            'content'    => $validated['content'],
        ]);

        $protocol->participants()->sync($validated['participant_ids'] ?? []);

        return redirect()->route('protocols.index')->with('success', 'Protokoll erfolgreich aktualisiert.');
    }

    public function sendEmail(Protocol $protocol)
    {
        if ($protocol->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return redirect()->route('protocols.mail.form', $protocol);
    }

    public function mailForm(Protocol $protocol)
    {
        if ($protocol->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $members = Member::where('tenant_id', $protocol->tenant_id)
            ->whereNotNull('email')
            ->orderBy('last_name')
            ->get();

        return view('protocols.send', compact('protocol', 'members'));
    }

    public function sendMail(Request $request, Protocol $protocol)
    {
        if ($protocol->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'email',
        ]);

        foreach ($validated['emails'] as $email) {
            Mail::to($email)->send(new ProtocolMail($protocol));
        }

        return redirect()->route('protocols.index')->with('success', 'Protokoll wurde an die ausgewählten Empfänger gesendet.');
    }
}

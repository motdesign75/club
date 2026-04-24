<?php

namespace App\Http\Controllers;

use App\Mail\ProtocolMail;
use App\Models\Member;
use App\Models\Protocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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
        $members = Member::forCurrentTenant()
            ->orderBy('last_name')
            ->get();

        return view('protocols.create', compact('members'));
    }

    public function store(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'type'              => 'required|string|max:255',
            'location'          => 'nullable|string|max:255',
            'start_time'        => 'nullable|date_format:H:i',
            'end_time'          => 'nullable|date_format:H:i',
            'content'           => 'required|string',
            'resolutions'       => 'nullable|string',
            'next_meeting'      => 'nullable|string',
            'participant_ids'   => 'nullable|array',
            'participant_ids.*' => [
                'integer',
                'exists:members,id',
            ],
            'attachments'       => 'nullable|array',
            'attachments.*'     => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx',
        ]);

        $attachmentPaths = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $attachmentPaths[] = $file->store(
                        'protocols/' . $tenantId . '/attachments',
                        'public'
                    );
                }
            }
        }

        $protocolData = [
            'tenant_id'     => $tenantId,
            'user_id'       => Auth::id(),
            'title'         => $validated['title'],
            'type'          => $validated['type'],
            'location'      => $validated['location'] ?? null,
            'start_time'    => $validated['start_time'] ?? null,
            'end_time'      => $validated['end_time'] ?? null,
            'content'       => $validated['content'],
            'resolutions'   => $validated['resolutions'] ?? null,
            'next_meeting'  => $validated['next_meeting'] ?? null,
        ];

        if (Schema::hasColumn('protocols', 'attachments')) {
            $protocolData['attachments'] = $attachmentPaths;
        } elseif (Schema::hasColumn('protocols', 'attachment_paths')) {
            $protocolData['attachment_paths'] = $attachmentPaths;
        } elseif (Schema::hasColumn('protocols', 'attachment_path')) {
            $protocolData['attachment_path'] = $attachmentPaths[0] ?? null;
        }

        $protocol = Protocol::create($protocolData);

        $participantIds = collect($validated['participant_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $allowedParticipantIds = Member::forCurrentTenant()
            ->whereIn('id', $participantIds)
            ->pluck('id')
            ->all();

        $protocol->participants()->sync($allowedParticipantIds);

        return redirect()
            ->route('protocols.index')
            ->with('success', 'Protokoll erfolgreich gespeichert.');
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

        $members = Member::forCurrentTenant()
            ->orderBy('last_name')
            ->get();

        $selected = $protocol->participants->pluck('id')->toArray();

        return view('protocols.edit', compact('protocol', 'members', 'selected'));
    }

    public function update(Request $request, Protocol $protocol)
    {
        if ($protocol->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $tenantId = auth()->user()->tenant_id;

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'type'              => 'required|string|max:255',
            'location'          => 'nullable|string|max:255',
            'start_time'        => 'nullable|date_format:H:i',
            'end_time'          => 'nullable|date_format:H:i',
            'content'           => 'required|string',
            'resolutions'       => 'nullable|string',
            'next_meeting'      => 'nullable|string',
            'participant_ids'   => 'nullable|array',
            'participant_ids.*' => [
                'integer',
                'exists:members,id',
            ],
            'attachments'       => 'nullable|array',
            'attachments.*'     => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx',
        ]);

        $attachmentPaths = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $attachmentPaths[] = $file->store(
                        'protocols/' . $tenantId . '/attachments',
                        'public'
                    );
                }
            }
        }

        $protocolData = [
            'title'         => $validated['title'],
            'type'          => $validated['type'],
            'location'      => $validated['location'] ?? null,
            'start_time'    => $validated['start_time'] ?? null,
            'end_time'      => $validated['end_time'] ?? null,
            'content'       => $validated['content'],
            'resolutions'   => $validated['resolutions'] ?? null,
            'next_meeting'  => $validated['next_meeting'] ?? null,
        ];

        if (!empty($attachmentPaths)) {
            if (Schema::hasColumn('protocols', 'attachments')) {
                $existing = is_array($protocol->attachments ?? null) ? $protocol->attachments : [];
                $protocolData['attachments'] = array_values(array_merge($existing, $attachmentPaths));
            } elseif (Schema::hasColumn('protocols', 'attachment_paths')) {
                $existing = is_array($protocol->attachment_paths ?? null) ? $protocol->attachment_paths : [];
                $protocolData['attachment_paths'] = array_values(array_merge($existing, $attachmentPaths));
            } elseif (Schema::hasColumn('protocols', 'attachment_path')) {
                $protocolData['attachment_path'] = $attachmentPaths[0];
            }
        }

        $protocol->update($protocolData);

        $participantIds = collect($validated['participant_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $allowedParticipantIds = Member::forCurrentTenant()
            ->whereIn('id', $participantIds)
            ->pluck('id')
            ->all();

        $protocol->participants()->sync($allowedParticipantIds);

        return redirect()
            ->route('protocols.index')
            ->with('success', 'Protokoll erfolgreich aktualisiert.');
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
            'emails'   => 'required|array',
            'emails.*' => 'email',
        ]);

        foreach ($validated['emails'] as $email) {
            Mail::to($email)->send(new ProtocolMail($protocol));
        }

        return redirect()
            ->route('protocols.index')
            ->with('success', 'Protokoll wurde an die ausgewählten Empfänger gesendet.');
    }
}
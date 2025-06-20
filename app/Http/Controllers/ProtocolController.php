<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('protocols.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'type'    => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Protocol::create([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id'   => Auth::id(),
            'title'     => $validated['title'],
            'type'      => $validated['type'],
            'content'   => $validated['content'],
        ]);

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

        return view('protocols.edit', compact('protocol'));
    }

    public function update(Request $request, Protocol $protocol)
    {
        if ($protocol->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'type'    => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $protocol->update($validated);

        return redirect()->route('protocols.index')->with('success', 'Protokoll erfolgreich aktualisiert.');
    }
}

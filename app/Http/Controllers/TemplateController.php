<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Services\TemplateParser;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::where(
            'tenant_id',
            auth()->user()->tenant_id
        )->orderBy('name')->get();

        return view('templates.index', compact('templates'));
    }


    public function create()
    {
        return view('templates.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'type' => ['required', 'string'],
        ]);

        $data['tenant_id'] = auth()->user()->tenant_id;

        Template::create($data);

        return redirect()
            ->route('templates.index')
            ->with('success', 'Vorlage gespeichert');
    }


    public function edit(Template $template)
    {
        $this->checkTenant($template);

        return view('templates.edit', compact('template'));
    }


    public function update(Request $request, Template $template)
    {
        $this->checkTenant($template);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'type' => ['required', 'string'],
        ]);

        $template->update($data);

        return redirect()
            ->route('templates.index')
            ->with('success', 'Vorlage aktualisiert');
    }


    public function destroy(Template $template)
    {
        $this->checkTenant($template);

        $template->delete();

        return back()->with('success', 'Vorlage gelöscht');
    }


    /**
     * Vorschau mit Variablenersetzung
     */
    public function preview(Template $template)
    {
        $this->checkTenant($template);

        $member = Member::where(
            'tenant_id',
            auth()->user()->tenant_id
        )->first();

        if (!$member) {
            abort(404, 'Kein Mitglied vorhanden');
        }

        $text = TemplateParser::parse(
            $template->body,
            $member
        );

        return view(
            'templates.preview',
            compact('template', 'text')
        );
    }


    /**
     * Tenant Schutz
     */
    private function checkTenant(Template $template): void
    {
        if (
            (string)$template->tenant_id !==
            (string)auth()->user()->tenant_id
        ) {
            abort(403);
        }
    }
}
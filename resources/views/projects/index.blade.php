@extends('layouts.app')

@section('title', 'Projekte')

@section('content')
@php
    // Clubano-Farblogik für Status
    $statusColors = [
        'active'   => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'on_hold'  => 'bg-amber-50 text-amber-700 ring-amber-200',
        'done'     => 'bg-slate-100 text-slate-700 ring-slate-300',
        // Fallback
        'default'  => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
    ];
    $firstProject = method_exists($projects, 'first') ? $projects->first() : ($projects[0] ?? null);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 space-y-6">

    <!-- Header -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Projekte</h1>
            <p class="text-sm text-slate-500">Übersicht deiner laufenden und abgeschlossenen Projekte.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if ($firstProject)
                <a href="{{ route('projects.gantt', $firstProject) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-white text-sm font-medium shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                    <x-heroicon-o-presentation-chart-bar class="h-5 w-5" />
                    Gantt-Diagramm
                </a>
            @endif
            <a href="{{ route('projects.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white text-sm font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                <x-heroicon-o-plus class="h-5 w-5" />
                Neues Projekt
            </a>
        </div>
    </div>

    <!-- Toolbar: Suche + (optionale) Filter -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="w-full sm:max-w-md">
                <label class="sr-only" for="q">Suche</label>
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-2.5 h-5 w-5 text-slate-400" />
                    <input
                        id="q"
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="In Projekten suchen…"
                        class="w-full rounded-lg border border-slate-300 pl-10 pr-3 py-2 text-sm placeholder:text-slate-400 focus:border-indigo-400 focus:ring-indigo-400"
                        />
                </div>
            </form>

            <div class="flex flex-wrap gap-2">
                {{-- einfache Status-Pills (optional, nur Optik – verlinke bei Bedarf auf ?status=...) --}}
                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">
                    Gesamt: {{ method_exists($projects, 'total') ? $projects->total() : $projects->count() }}
                </span>
            </div>
        </div>
    </div>

    @if(session('status'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @if($projects->isEmpty())
        <!-- Empty State -->
        <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-white p-10 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50">
                <x-heroicon-o-rectangle-stack class="h-6 w-6 text-indigo-500" />
            </div>
            <h3 class="text-base font-semibold text-slate-900">Noch keine Projekte vorhanden</h3>
            <p class="mt-1 text-sm text-slate-500">Lege dein erstes Projekt an und starte mit Aufgaben & Gantt.</p>
            <div class="mt-4">
                <a href="{{ route('projects.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white text-sm font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Neues Projekt
                </a>
            </div>
        </div>
    @else
        <!-- Mobile: Kartenliste -->
        <div class="grid gap-4 sm:hidden">
            @foreach($projects as $project)
                @php
                    $status = $project->status ?? 'default';
                    $badge  = $statusColors[$status] ?? $statusColors['default'];
                    $start  = optional($project->starts_at)->format('d.m.Y');
                    $end    = optional($project->ends_at)->format('d.m.Y');
                @endphp
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="truncate text-base font-semibold text-slate-900">{{ $project->name }}</h3>
                            <p class="mt-1 line-clamp-2 text-sm text-slate-600">
                                {{ \Illuminate\Support\Str::limit($project->description, 160) ?: '—' }}
                            </p>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 ring-1 {{ $badge }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-60"></span>
                                    {{ $project->status ?? '—' }}
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 px-2 py-0.5 ring-1 ring-slate-200">
                                    <x-heroicon-o-calendar class="h-4 w-4 text-slate-400" />
                                    {{ $start ?: '—' }} – {{ $end ?: '—' }}
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-50 px-2 py-0.5 ring-1 ring-slate-200">
                                    <x-heroicon-o-user class="h-4 w-4 text-slate-400" />
                                    {{ optional($project->owner)->name ?? '—' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <a href="{{ route('projects.show', $project) }}"
                           class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                            Anzeigen
                        </a>
                        <a href="{{ route('projects.edit', $project) }}"
                           class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700">
                            Bearbeiten
                        </a>
                        <a href="{{ route('projects.gantt', $project) }}"
                           class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700">
                            Gantt
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop/Tablet: Tabelle -->
        <div class="hidden sm:block overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-slate-50 text-left text-sm font-semibold text-slate-700">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Zeitraum</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Owner</th>
                            <th class="px-4 py-3">Erstellt</th>
                            <th class="px-4 py-3 text-right">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($projects as $project)
                            @php
                                $status = $project->status ?? 'default';
                                $badge  = $statusColors[$status] ?? $statusColors['default'];
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 align-top">
                                    <div class="font-medium text-slate-900">{{ $project->name }}</div>
                                    <div class="mt-0.5 text-xs text-slate-500">
                                        {{ \Illuminate\Support\Str::limit($project->description, 120) ?: '—' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 align-top text-slate-700">
                                    {{ optional($project->starts_at)->format('d.m.Y') ?: '—' }}
                                    –
                                    {{ optional($project->ends_at)->format('d.m.Y') ?: '—' }}
                                </td>
                                <td class="px-4 py-3 align-top">
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 {{ $badge }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current opacity-60"></span>
                                        {{ $project->status ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-top text-slate-700">
                                    {{ optional($project->owner)->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3 align-top text-slate-600">
                                    {{ $project->created_at?->timezone(config('app.timezone', 'Europe/Berlin'))->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-4 py-3 align-top">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('projects.show', $project) }}"
                                           class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                            Anzeigen
                                        </a>
                                        <a href="{{ route('projects.edit', $project) }}"
                                           class="rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700">
                                            Bearbeiten
                                        </a>
                                        <a href="{{ route('projects.gantt', $project) }}"
                                           class="rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700">
                                            Gantt
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($projects, 'links'))
                <div class="border-t border-slate-200 bg-slate-50 px-3 py-2">
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <div>
                            @if(method_exists($projects, 'firstItem'))
                                Zeige {{ $projects->firstItem() }}–{{ $projects->lastItem() }} von {{ $projects->total() }}
                            @else
                                Gesamt: {{ $projects->count() }}
                            @endif
                        </div>
                        <div class="text-sm">
                            {{ $projects->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

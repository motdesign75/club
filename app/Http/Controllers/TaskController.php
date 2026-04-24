<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Project, Task};

class TaskController extends Controller
{
    // Formular: neue Aufgabe
    public function create(Project $project)
    {
        if ((string)auth()->user()->tenant_id !== (string)$project->tenant_id) abort(404);

        return view('tasks.create', [
            'title'   => 'Neue Aufgabe',
            'project' => $project,
        ]);
    }

    // Speichern: neue Aufgabe
    public function store(Request $request, Project $project)
    {
        if ((string)auth()->user()->tenant_id !== (string)$project->tenant_id) abort(404);

        $data = $request->validate([
            'title'        => ['required','string','max:255'],
            'description'  => ['nullable','string'],
            'plan_start'   => ['nullable','date'],
            'plan_end'     => ['nullable','date','after_or_equal:plan_start'],
            'status'       => ['nullable','in:open,in_progress,blocked,done'],
            'percent_done' => ['nullable','integer','between:0,100'],
            'assignee_id'  => ['nullable','integer','exists:users,id'],
            'priority'     => ['nullable','integer','between:1,5'],
            'type'         => ['nullable','in:task,milestone'],
        ]);

        $data = array_merge([
            'tenant_id'    => auth()->user()->tenant_id,
            'status'       => 'open',
            'percent_done' => 0,
            'priority'     => 3,
            'type'         => 'task',
        ], $data);

        $project->tasks()->create($data);

        return redirect()->route('projects.show', $project)->with('success', 'Aufgabe wurde angelegt.');
    }

    // Formular: Aufgabe bearbeiten
    public function edit(Project $project, Task $task)
    {
        if ((string)auth()->user()->tenant_id !== (string)$project->tenant_id) abort(404);
        if ((string)$task->project_id !== (string)$project->id) abort(404);

        return view('tasks.edit', [
            'title'   => 'Aufgabe bearbeiten',
            'project' => $project,
            'task'    => $task,
        ]);
    }

    // Speichern: Aufgabe aktualisieren
    public function update(Request $request, Project $project, Task $task)
    {
        if ((string)auth()->user()->tenant_id !== (string)$project->tenant_id) abort(404);
        if ((string)$task->project_id !== (string)$project->id) abort(404);

        $data = $request->validate([
            'title'        => ['required','string','max:255'],
            'description'  => ['nullable','string'],
            'plan_start'   => ['nullable','date'],
            'plan_end'     => ['nullable','date','after_or_equal:plan_start'],
            'status'       => ['required','in:open,in_progress,blocked,done'],
            'percent_done' => ['required','integer','between:0,100'],
            'assignee_id'  => ['nullable','integer','exists:users,id'],
            'priority'     => ['required','integer','between:1,5'],
            'type'         => ['required','in:task,milestone'],
        ]);

        $task->update($data);

        return redirect()->route('projects.show', $project)->with('success', 'Aufgabe wurde aktualisiert.');
    }
}

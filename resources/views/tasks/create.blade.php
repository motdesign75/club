<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <title>{{ $title ?? 'Neue Aufgabe' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    html,body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Arial;margin:0;padding:0}
    .wrap{max-width:800px;margin:40px auto;padding:24px}
    label{display:block;margin:.75rem 0 .25rem;font-weight:600}
    input,textarea,select{width:100%;padding:.5rem .6rem;border:1px solid #d4d4d8;border-radius:.5rem}
    .row{display:flex;gap:1rem}.row>div{flex:1}
    .actions{margin-top:1rem;display:flex;gap:.75rem}
    .btn{display:inline-flex;gap:.5rem;background:#18181b;color:#fff;padding:.5rem .75rem;border-radius:.5rem;text-decoration:none}
    .btn.secondary{background:#e5e7eb;color:#111827}
    .error{color:#b91c1c;font-size:.9rem;margin-top:.25rem}
  </style>
</head>
<body>
<div class="wrap">
  <a href="{{ route('projects.show', $project) }}" style="text-decoration:none">← Zurück zum Projekt</a>
  <h1 style="margin:8px 0 12px">Neue Aufgabe für: {{ $project->name }}</h1>

  @if ($errors->any())
    <div style="background:#fef2f2;color:#7f1d1d;border:1px solid #fecaca;border-radius:.5rem;padding:.75rem;margin:.75rem 0;">
      <strong>Bitte prüfen:</strong>
      <ul>
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('projects.tasks.store', $project) }}">
    @csrf

    <label for="title">Titel *</label>
    <input id="title" name="title" type="text" value="{{ old('title') }}" required>
    @error('title') <div class="error">{{ $message }}</div> @enderror

    <label for="description">Beschreibung</label>
    <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>

    <div class="row">
      <div>
        <label for="plan_start">Geplanter Start</label>
        <input id="plan_start" name="plan_start" type="date" value="{{ old('plan_start') }}">
      </div>
      <div>
        <label for="plan_end">Geplantes Ende</label>
        <input id="plan_end" name="plan_end" type="date" value="{{ old('plan_end') }}">
      </div>
    </div>

    <div class="row">
      <div>
        <label for="status">Status</label>
        <select id="status" name="status">
          <option value="open" {{ old('status','open')==='open'?'selected':'' }}>Offen</option>
          <option value="in_progress" {{ old('status')==='in_progress'?'selected':'' }}>In Arbeit</option>
          <option value="blocked" {{ old('status')==='blocked'?'selected':'' }}>Blockiert</option>
          <option value="done" {{ old('status')==='done'?'selected':'' }}>Erledigt</option>
        </select>
      </div>
      <div>
        <label for="percent_done">% erledigt</label>
        <input id="percent_done" name="percent_done" type="number" min="0" max="100" step="1" value="{{ old('percent_done', 0) }}">
      </div>
    </div>

    <div class="row">
      <div>
        <label for="priority">Priorität (1=hoch ... 5=niedrig)</label>
        <input id="priority" name="priority" type="number" min="1" max="5" step="1" value="{{ old('priority', 3) }}">
      </div>
      <div>
        <label for="type">Typ</label>
        <select id="type" name="type">
          <option value="task" {{ old('type','task')==='task'?'selected':'' }}>Aufgabe</option>
          <option value="milestone" {{ old('type')==='milestone'?'selected':'' }}>Meilenstein</option>
        </select>
      </div>
    </div>

    <div class="actions">
      <button class="btn" type="submit">Speichern</button>
      <a class="btn secondary" href="{{ route('projects.show', $project) }}">Abbrechen</a>
    </div>
  </form>
</div>
</body>
</html>

<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Neues Projekt' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html,body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Arial;margin:0;padding:0}
        .wrap{max-width:800px;margin:40px auto;padding:24px}
        label{display:block;margin:.75rem 0 .25rem;font-weight:600}
        input,textarea,select{width:100%;padding:.5rem .6rem;border:1px solid #d4d4d8;border-radius:.5rem}
        .row{display:flex;gap:1rem}
        .row>div{flex:1}
        .actions{margin-top:1rem;display:flex;gap:.75rem}
        .btn{display:inline-flex;gap:.5rem;background:#18181b;color:#fff;padding:.5rem .75rem;border-radius:.5rem;text-decoration:none}
        .btn.secondary{background:#e5e7eb;color:#111827}
        .error{color:#b91c1c;font-size:.9rem;margin-top:.25rem}
    </style>
</head>
<body>
<div class="wrap">
    <h1 style="margin:0 0 12px;">Neues Projekt</h1>

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

    <form method="post" action="{{ route('projects.store') }}">
        @csrf

        <label for="name">Name *</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required>
        @error('name') <div class="error">{{ $message }}</div> @enderror

        <label for="description">Beschreibung</label>
        <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>

        <div class="row">
            <div>
                <label for="starts_at">Start (optional)</label>
                <input id="starts_at" name="starts_at" type="date" value="{{ old('starts_at') }}">
                @error('starts_at') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="ends_at">Ende (optional)</label>
                <input id="ends_at" name="ends_at" type="date" value="{{ old('ends_at') }}">
                @error('ends_at') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active" {{ old('status','active')==='active'?'selected':'' }}>Aktiv</option>
                    <option value="on_hold" {{ old('status')==='on_hold'?'selected':'' }}>Pausiert</option>
                    <option value="finished" {{ old('status')==='finished'?'selected':'' }}>Abgeschlossen</option>
                </select>
            </div>
            <div>
                <label for="owner_id">Verantwortlich (User-ID, optional)</label>
                <input id="owner_id" name="owner_id" type="number" value="{{ old('owner_id') }}" min="1" step="1">
                @error('owner_id') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="actions">
            <button class="btn" type="submit">Speichern</button>
            <a class="btn secondary" href="{{ route('projects.index') }}">Abbrechen</a>
        </div>
    </form>
</div>
</body>
</html>

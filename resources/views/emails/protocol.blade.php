<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Protokoll</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

    <h2>📋 {{ $protocol->title }}</h2>

    <p><strong>Typ:</strong> {{ $protocol->type }}</p>

    @if($protocol->location)
        <p><strong>Ort:</strong> {{ $protocol->location }}</p>
    @endif

    @if($protocol->start_time)
        <p><strong>Beginn:</strong> {{ $protocol->start_time }}</p>
    @endif

    @if($protocol->end_time)
        <p><strong>Ende:</strong> {{ $protocol->end_time }}</p>
    @endif

    <hr>

    {{-- Teilnehmer --}}
    @if($protocol->participants && $protocol->participants->count())
        <h3>👥 Teilnehmer</h3>
        <ul>
            @foreach($protocol->participants as $member)
                <li>{{ $member->full_name }}</li>
            @endforeach
        </ul>
    @endif

    {{-- Beschlüsse --}}
    @if($protocol->resolutions)
        <h3>📌 Beschlüsse / Ergebnisse</h3>
        <p>{!! nl2br(e($protocol->resolutions)) !!}</p>
    @endif

    {{-- Nächstes Treffen --}}
    @if($protocol->next_meeting)
        <h3>📅 Nächstes Treffen</h3>
        <p>{!! nl2br(e($protocol->next_meeting)) !!}</p>
    @endif

    {{-- Inhalt --}}
    <h3>📝 Protokoll</h3>
    <div>
        {!! $protocol->content !!}
    </div>

    {{-- Hinweis zu Anhängen --}}
    @if(!empty($protocol->attachments))
        <hr>
        <p><strong>📎 Anhänge:</strong> Diese E-Mail enthält {{ count($protocol->attachments) }} Datei(en) im Anhang.</p>
    @endif

</body>
</html>
@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Protokoll: {{ $protocol->title }}</title>
</head>
<body style="font-family: sans-serif; color: #333; line-height: 1.6;">

    <h2 style="color: #2d3748;">📄 Protokoll: {{ $protocol->title }}</h2>

    <p><strong>Typ:</strong> {{ $protocol->type }}</p>
    <p><strong>Erstellt am:</strong> {{ $protocol->created_at->format('d.m.Y H:i') }}</p>

    @if (!empty($protocol->location))
        <p><strong>Ort:</strong> {{ $protocol->location }}</p>
    @endif

    @if (!empty($protocol->start_time))
        <p><strong>Beginn:</strong> {{ \Carbon\Carbon::parse($protocol->start_time)->format('H:i') }} Uhr</p>
    @endif

    @if (!empty($protocol->end_time))
        <p><strong>Ende:</strong> {{ \Carbon\Carbon::parse($protocol->end_time)->format('H:i') }} Uhr</p>
    @endif

    @if (!empty($protocol->participants) && $protocol->participants->count())
        <p><strong>Teilnehmer:</strong>
            {{ $protocol->participants->map(fn($member) => trim($member->first_name . ' ' . $member->last_name))->join(', ') }}
        </p>
    @else
        <p><strong>Teilnehmer:</strong> Keine Angaben</p>
    @endif

    <hr>

    <div style="white-space: pre-wrap;">
        {!! $protocol->content !!}
    </div>

    <hr>

    <p style="font-size: 12px; color: #999;">
        Dieses Protokoll wurde automatisch über <strong>Clubano.de</strong> generiert.<br>
        Bitte bewahren Sie diese E-Mail ggf. für Ihre Unterlagen auf.
    </p>

</body>
</html>

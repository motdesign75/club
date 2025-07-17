<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Rechnung {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 2.5cm 2cm 2.5cm 2cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
            color: #111;
        }

        .header, .footer {
            position: fixed;
            left: 0;
            right: 0;
            width: 100%;
        }

        .header {
            top: -2.2cm;
        }

        .footer {
            bottom: -2.2cm;
            font-size: 8pt;
            color: #666;
        }

        .footer-table {
            width: 100%;
            border-top: 1px solid #999;
            padding-top: 5px;
        }

        .address-block {
            margin-top: 4.5cm;
            margin-bottom: 2cm;
        }

        .right { text-align: right; }
        .left { text-align: left; }
        .center { text-align: center; }
        .bold { font-weight: bold; }

        .invoice-title {
            font-size: 16pt;
            font-weight: bold;
            margin-top: 0.5cm;
            margin-bottom: 1.5cm;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5cm;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #ccc;
            padding: 6px 10px;
        }

        .details-table th {
            background-color: #f5f5f5;
        }

        .summary-table {
            width: 100%;
            margin-top: 1cm;
        }

        .summary-table td {
            padding: 4px;
        }

        .summary-table .label {
            text-align: right;
            width: 80%;
        }

        .summary-table .value {
            text-align: right;
            width: 20%;
        }

        .signature {
            margin-top: 2cm;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: auto;
            z-index: -1;
        }

        .logo {
            max-height: 80px;
        }
    </style>
</head>
<body>
@php
    $tenant = $invoice->member->tenant;
    $member = $invoice->member;
    $positions = $invoice->items ?? collect();
    $discount = $invoice->discount ?? 0;
    $taxRate = $invoice->tax_rate ?? 0;
    $net = $positions->sum(fn($item) => $item->quantity * $item->unit_price);
    $discountAmount = $net * ($discount / 100);
    $netAfterDiscount = $net - $discountAmount;
    $taxAmount = $netAfterDiscount * ($taxRate / 100);
    $total = $netAfterDiscount + $taxAmount;

    // Anrede individuell behandeln
    switch($member->anrede) {
        case 'Herr':
            $anrede = 'Sehr geehrter Herr ' . $member->last_name;
            break;
        case 'Frau':
            $anrede = 'Sehr geehrte Frau ' . $member->last_name;
            break;
        case 'Divers':
            $anrede = 'Guten Tag ' . $member->first_name . ' ' . $member->last_name;
            break;
        case 'Lieber':
        case 'Liebe':
            $anrede = $member->anrede . ' ' . $member->first_name;
            break;
        default:
            $anrede = 'Sehr geehrte Damen und Herren';
    }
@endphp

{{-- Briefpapier als Bild – auf allen Seiten --}}
@if($tenant->use_letterhead && $tenant->pdf_template)
    @php
        $ext = pathinfo($tenant->pdf_template, PATHINFO_EXTENSION);
    @endphp
    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
        <img src="{{ storage_path('app/public/' . $tenant->pdf_template) }}" class="background">
    @endif
@endif

{{-- Header (wenn kein Briefbogen verwendet wird) --}}
@unless($tenant->use_letterhead)
<div class="header">
    <table width="100%">
        <tr>
            <td style="width: 60%;">
                @if($tenant->logo && file_exists(storage_path('app/public/' . $tenant->logo)))
                    <img src="{{ storage_path('app/public/' . $tenant->logo) }}" class="logo"><br>
                @endif
            </td>
            <td class="right" style="width: 40%;">
                <strong>{{ $tenant->name }}</strong><br>
                {{ $tenant->address }}<br>
                {{ $tenant->zip }} {{ $tenant->city }}<br>
                Tel: {{ $tenant->phone ?? '–' }}<br>
                E-Mail: {{ $tenant->email }}
            </td>
        </tr>
    </table>
</div>
@endunless

{{-- Footer (wenn kein Briefbogen verwendet wird) --}}
@unless($tenant->use_letterhead)
<div class="footer">
    <table class="footer-table">
        <tr>
            <td style="width: 33%; text-align: left;">
                <strong>IBAN:</strong> {{ $tenant->iban ?? '—' }}<br>
                <strong>BIC:</strong> {{ $tenant->bic ?? '—' }}<br>
                <strong>Bank:</strong> {{ $tenant->bank_name ?? '—' }}
            </td>
            <td style="width: 33%; text-align: center;">
                {{ $tenant->name }}<br>
                Register-Nr.: {{ $tenant->register_number ?? '—' }}<br>
                Vorsitz: {{ $tenant->chairman_name ?? '—' }}
            </td>
            <td style="width: 33%; text-align: right;">
                {{ $tenant->address }}<br>
                {{ $tenant->zip }} {{ $tenant->city }}<br>
                Tel: {{ $tenant->phone ?? '—' }}<br>
                E-Mail: {{ $tenant->email }}
            </td>
        </tr>
    </table>
</div>
@endunless

{{-- Empfängeradresse --}}
<div class="address-block">
    {{ $member->anrede }} {{ $member->first_name }} {{ $member->last_name }}<br>
    {{ $member->street }}<br>
    {{ $member->zip }} {{ $member->city }}
</div>

{{-- Ort & Datum --}}
<div class="right">
    {{ $tenant->city ?? 'Ort' }}, {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}
</div>

{{-- Titel --}}
<div class="invoice-title">
    Rechnung Nr. {{ $invoice->invoice_number }}
</div>

{{-- Personalisierte Anrede --}}
<p>{{ $anrede }},</p>
<p>für Ihre Mitgliedschaft und Leistungen stellen wir Ihnen folgende Positionen in Rechnung:</p>

{{-- Tabelle mit Positionen --}}
<table class="details-table">
    <thead>
        <tr>
            <th>Pos.</th>
            <th>Beschreibung</th>
            <th class="right">Menge</th>
            <th>Einheit</th>
            <th class="right">Einzelpreis</th>
            <th class="right">Gesamt</th>
        </tr>
    </thead>
    <tbody>
        @foreach($positions as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->description }}</td>
            <td class="right">{{ $item->quantity }}</td>
            <td>{{ $item->unit ?? 'Stück' }}</td>
            <td class="right">{{ number_format($item->unit_price, 2, ',', '.') }} €</td>
            <td class="right">{{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }} €</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Zusammenfassung --}}
<table class="summary-table">
    <tr>
        <td class="label">Zwischensumme (Netto):</td>
        <td class="value">{{ number_format($net, 2, ',', '.') }} €</td>
    </tr>
    @if($discount > 0)
    <tr>
        <td class="label">Rabatt ({{ $discount }} %):</td>
        <td class="value">– {{ number_format($discountAmount, 2, ',', '.') }} €</td>
    </tr>
    @endif
    <tr>
        <td class="label">Nettobetrag:</td>
        <td class="value">{{ number_format($netAfterDiscount, 2, ',', '.') }} €</td>
    </tr>
    <tr>
        <td class="label">USt. ({{ $taxRate }} %):</td>
        <td class="value">{{ number_format($taxAmount, 2, ',', '.') }} €</td>
    </tr>
    <tr>
        <td class="label bold">Gesamtbetrag (Brutto):</td>
        <td class="value bold">{{ number_format($total, 2, ',', '.') }} €</td>
    </tr>
</table>

{{-- Zahlungsziel --}}
<p style="margin-top: 1cm;">Bitte überweisen Sie den Betrag innerhalb von 14 Tagen auf das oben genannte Konto.</p>

{{-- Grußformel --}}
<div class="signature">
    Mit freundlichen Grüßen<br><br>
    {{ $tenant->name }}
</div>

</body>
</html>

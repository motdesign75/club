<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Rechnung {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        @page {
            margin: 2.5cm 2cm 2.5cm 2cm;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: -1;
        }

        .header, .footer {
            width: 100%;
            position: fixed;
            left: 0;
        }

        .header {
            top: -2.2cm;
            font-size: 10pt;
        }

        .footer {
            bottom: -2.3cm;
            font-size: 9pt;
            padding-top: 5px;
        }

        .footer-table {
            width: 100%;
            border-top: 1px solid #999;
            padding-top: 5px;
        }

        .footer-table td {
            vertical-align: top;
            padding-top: 5px;
        }

        .address-block {
            margin-top: 4.5cm;
            margin-bottom: 2cm;
        }

        .meta {
            margin-bottom: 1cm;
        }

        .meta td {
            padding: 3px 0;
        }

        .content {
            margin-top: 1cm;
        }

        .signature {
            margin-top: 2cm;
        }

        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .small {
            font-size: 9pt;
        }
    </style>
</head>
<body>
    @php
        $tenant = $invoice->member->tenant;
    @endphp

    {{-- Briefpapier als Hintergrundbild --}}
    @if($tenant->use_letterhead && $tenant->pdf_template && file_exists(storage_path('app/public/' . $tenant->pdf_template)))
        <img src="{{ storage_path('app/public/' . $tenant->pdf_template) }}" class="background">
    @endif

    {{-- Kopfzeile – nur wenn kein Briefpapier verwendet wird --}}
    @unless($tenant->use_letterhead)
        <div class="header">
            <table width="100%">
                <tr>
                    <td style="width: 60%;">
                        <strong>{{ $tenant->name }}</strong><br>
                        {{ $tenant->address }}<br>
                        {{ $tenant->zip }} {{ $tenant->city }}<br>
                        Tel: {{ $tenant->phone ?? '–' }}<br>
                        E-Mail: {{ $tenant->email }}
                    </td>
                    <td class="right" style="width: 40%;">
                        @if($tenant->logo && file_exists(storage_path('app/public/' . $tenant->logo)))
                            <img src="{{ storage_path('app/public/' . $tenant->logo) }}" alt="Logo" style="max-height: 80px;">
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    @endunless

    {{-- Fußzeile – nur wenn kein Briefpapier verwendet wird --}}
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
                        <strong>{{ $tenant->name }}</strong><br>
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
        {{ $invoice->member->full_name }}<br>
        {{ $invoice->member->street }}<br>
        {{ $invoice->member->zip }} {{ $invoice->member->city }}
    </div>

    {{-- Ort und Datum --}}
    <div class="right">
        {{ $tenant->city ?? 'Ort' }}, {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}
    </div>

    {{-- Betreff --}}
    <p class="bold" style="margin-top: 1.5cm;">Rechnung Nr. {{ $invoice->invoice_number }}</p>

    {{-- Rechnungstext --}}
    <div class="content">
        <p>Sehr geehrte Damen und Herren,</p>

        <p>für Ihre Mitgliedschaft stellen wir Ihnen folgenden Beitrag in Rechnung:</p>

        <table style="width: 100%; margin-top: 1cm; margin-bottom: 1cm;">
            <tr>
                <td class="bold">Bezeichnung</td>
                <td class="right bold">Betrag</td>
            </tr>
            <tr>
                <td>{{ $invoice->description ?? 'Mitgliedsbeitrag' }}</td>
                <td class="right">{{ number_format($invoice->amount, 2, ',', '.') }} €</td>
            </tr>
        </table>

        <p>Bitte überweisen Sie den Betrag innerhalb von 14 Tagen auf das oben angegebene Konto.</p>

        <p>Vielen Dank für Ihre Unterstützung!</p>

        <div class="signature">
            Mit freundlichen Grüßen<br><br>
            {{ $tenant->name }}
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Rechnung {{ $invoice->invoice_number }}</title>

<style>
@page {
    margin: 25mm 20mm 20mm 20mm;
}

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 10pt;
    color: #000;
}

/* ================= HEADER ================= */

.header {
    margin-bottom: 20mm;
}

.logo {
    height: 50px;
}

/* ================= ADDRESS ================= */

.address-block {
    position: absolute;
    top: 65mm;
    left: 20mm;
    width: 85mm;
}

.sender-line {
    font-size: 6pt;
    color: #666;
    margin-bottom: 2mm;
	text-decoration:underline;
}

/* ================= INFO ================= */

.info-block {
    position: absolute;
    top: 50mm;
    right: 20mm;
    width: 60mm;
    text-align: right;
}

/* ================= CONTENT ================= */

.content {
    margin-top: 85mm; /* WICHTIG: verhindert Überlagerung */
}

/* Titel */
.invoice-title {
    font-size: 14pt;
    font-weight: bold;
    margin-bottom: 8mm;
}

/* Tabelle */
.details-table {
    width: 100%;
    border-collapse: collapse;
}

.details-table th {
    border-bottom: 1.5px solid #000;
    padding: 6px;
    font-size: 9pt;
    text-align: left;
}

.details-table td {
    border-bottom: 0.5px solid #ccc;
    padding: 5px;
    font-size: 9pt;
}

/* Summen */
.summary {
    width: 100%;
    margin-top: 8mm;
}

.summary td {
    padding: 3px;
}

.summary .label {
    text-align: right;
}

.summary .value {
    text-align: right;
    width: 35mm;
}

.total {
    border-top: 1.5px solid #000;
    font-weight: bold;
    font-size: 11pt;
}

/* ================= FOOTER ================= */

.footer {
    position: fixed;
    bottom: 10mm;
    left: 20mm;
    right: 20mm;
    font-size: 7pt;
    color: #444;
}

.footer-line {
    border-top: 0.5px solid #999;
    margin-bottom: 2mm;
}

.footer table {
    width: 100%;
}

.footer td {
    vertical-align: top;
}

/* ================= HELPERS ================= */

.right { text-align: right; }
.center { text-align: center; }

</style>
</head>

<body>

@php
$tenant = $invoice->member->tenant;
$member = $invoice->member;
$positions = $invoice->items ?? collect();

$discount = $invoice->discount ?? 0;
$taxRate = $invoice->tax_rate ?? 0;

$net = $positions->sum(fn($i) => $i->quantity * $i->unit_price);
$discountAmount = $net * ($discount / 100);
$netAfterDiscount = $net - $discountAmount;
$taxAmount = $netAfterDiscount * ($taxRate / 100);
$total = $netAfterDiscount + $taxAmount;
@endphp

{{-- ================= HEADER ================= --}}
<div class="header">
    <table width="100%">
        <tr>
            <td>
                <strong>{{ $tenant->name }}</strong><br>
                {{ $tenant->address }}<br>
                {{ $tenant->zip }} {{ $tenant->city }}
            </td>
            <td class="right">
                @if($tenant->logo && file_exists(storage_path('app/public/' . $tenant->logo)))
                    <img src="{{ storage_path('app/public/' . $tenant->logo) }}" class="logo">
                @endif
            </td>
        </tr>
    </table>
</div>

{{-- ================= ADDRESS ================= --}}
<div class="address-block">
    <div class="sender-line">
        {{ $tenant->name }} · {{ $tenant->address }} · {{ $tenant->zip }} {{ $tenant->city }}
    </div>

    {{ $member->anrede }} {{ $member->first_name }} {{ $member->last_name }}<br>
    {{ $member->street }}<br>
    {{ $member->zip }} {{ $member->city }}
</div>

{{-- ================= INFO ================= --}}
<div class="info-block">
    Rechnung: {{ $invoice->invoice_number }}<br>
    Datum: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}<br>
    Kunde: {{ $member->member_id ?? '-' }}
</div>

{{-- ================= CONTENT ================= --}}
<div class="content">

<div class="invoice-title">
    Rechnung {{ $invoice->invoice_number }}
</div>

<p>Sehr geehrte Damen und Herren,</p>

<p>
vielen Dank für Ihre Mitgliedschaft. Wir stellen Ihnen folgende Leistungen in Rechnung:
</p>

<table class="details-table">
<thead>
<tr>
<th style="width:10%">Pos.</th>
<th>Beschreibung</th>
<th style="width:15%" class="right">Menge</th>
<th style="width:20%" class="right">Preis</th>
<th style="width:20%" class="right">Summe</th>
</tr>
</thead>
<tbody>
@foreach($positions as $i => $item)
<tr>
<td>{{ $i+1 }}</td>
<td>{{ $item->description }}</td>
<td class="right">{{ number_format($item->quantity,2,',','.') }}</td>
<td class="right">{{ number_format($item->unit_price,2,',','.') }} €</td>
<td class="right">{{ number_format($item->quantity * $item->unit_price,2,',','.') }} €</td>
</tr>
@endforeach
</tbody>
</table>

<table class="summary">
<tr>
<td class="label">Netto:</td>
<td class="value">{{ number_format($netAfterDiscount,2,',','.') }} €</td>
</tr>
<tr>
<td class="label">USt ({{ $taxRate }}%):</td>
<td class="value">{{ number_format($taxAmount,2,',','.') }} €</td>
</tr>
<tr class="total">
<td class="label">Gesamt:</td>
<td class="value">{{ number_format($total,2,',','.') }} €</td>
</tr>
</table>

<p style="margin-top:10mm;">
Bitte überweisen Sie den Betrag innerhalb von 14 Tagen.
</p>

<p style="margin-top:15mm;">
Mit freundlichen Grüßen<br><br>
{{ $tenant->name }}
</p>

</div>

{{-- ================= FOOTER ================= --}}
<div class="footer">

<div class="footer-line"></div>

<table>
<tr>
<td style="width:33%;">
{{ $tenant->name }}<br>
{{ $tenant->address }}<br>
{{ $tenant->zip }} {{ $tenant->city }}
</td>

<td style="width:33%;" class="left">
IBAN: {{ $tenant->iban ?? '—' }}<br>
BIC: {{ $tenant->bic ?? '—' }}
</td>

<td style="width:33%;" class="right">
{{ $tenant->email }}<br>
{{ $tenant->phone ?? '' }}
</td>
</tr>
</table>

</div>

</body>
</html>
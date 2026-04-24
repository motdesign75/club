<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Buchungsjournal</title>

<style>
@page {
    margin: 12mm 12mm;
}

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 9pt;
    color: #000;
}

/* HEADER */
.header-table {
    width: 100%;
    margin-bottom: 10px;
}

.header-left {
    font-size: 11pt;
}

.header-right {
    text-align: right;
    font-size: 9pt;
}

/* TITLE */
h1 {
    text-align: center;
    margin: 10px 0;
}

/* META */
.meta {
    font-size: 9pt;
    margin-bottom: 10px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    display: table-header-group;
}

tfoot {
    display: table-footer-group;
}

th, td {
    border: 1px solid #000;
    padding: 4px;
    vertical-align: top;
}

th {
    background: #eee;
}

/* COLUMN WIDTHS */
th:nth-child(1), td:nth-child(1) { width: 5%; }
th:nth-child(2), td:nth-child(2) { width: 10%; }
th:nth-child(3), td:nth-child(3) { width: 15%; }
th:nth-child(4), td:nth-child(4) { width: 30%; }
th:nth-child(5), td:nth-child(5) { width: 15%; }
th:nth-child(6), td:nth-child(6) { width: 15%; }
th:nth-child(7), td:nth-child(7) { width: 10%; }

/* TEXT HANDLING */
td:nth-child(4) {
    word-break: break-word;
}

/* ALIGNMENTS */
.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

tfoot td {
    font-weight: bold;
}

/* UI */
.no-print {
    margin-bottom: 15px;
}

@media print {
    .no-print {
        display: none;
    }
}
</style>
</head>

<body>

{{-- BUTTONS --}}
<div class="no-print">
    <button onclick="window.print()">🖨️ Drucken</button>

    <a href="{{ route('transactions.journal.pdf', [
            'filter' => $filter,
            'year' => $year,
            'month' => $month
        ]) }}"
       style="margin-left:10px; padding:6px 10px; background:#dc2626; color:#fff; text-decoration:none; border-radius:4px;">
        📄 PDF Export
    </a>
</div>

{{-- HEADER --}}
<table class="header-table">
    <tr>
        <td class="header-left">
            <strong>{{ $tenant->name ?? 'Verein' }}</strong><br>
        </td>

        <td class="header-right">
            Erstellt am:<br>
            {{ now()->format('d.m.Y H:i') }}
        </td>
    </tr>
</table>

<h1>Buchungsjournal</h1>

<div class="meta">
    Zeitraum:
    Jahr {{ $year ?? 'Alle' }}
    @if($month)
        | Monat {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
    @endif
</div>

<table>
    <thead>
        <tr>
            <th class="text-center">Nr.</th>
            <th>Datum</th>
            <th>Belegnr.</th>
            <th>Beschreibung</th>
            <th>Soll</th>
            <th>Haben</th>
            <th class="text-right">Betrag (€)</th>
        </tr>
    </thead>

    <tbody>

    @php $i = 1; @endphp

    @foreach($transactions as $t)
        @php
            $isExpense = in_array(optional($t->account_from)->type, ['bank','kasse']);
        @endphp

        <tr>
            <td class="text-center">{{ $i++ }}</td>

            <td>
                {{ \Carbon\Carbon::parse($t->date)->format('d.m.Y') }}
            </td>

            <td>
                {{ $t->receipt_number ?? '-' }}
            </td>

            <td>
                {{ $t->description }}
            </td>

            <td>
                {{ $t->account_from->name ?? '-' }}
            </td>

            <td>
                {{ $t->account_to->name ?? '-' }}
            </td>

            <td class="text-right">
                @if($isExpense)
                    -{{ number_format($t->amount, 2, ',', '.') }}
                @else
                    {{ number_format($t->amount, 2, ',', '.') }}
                @endif
            </td>
        </tr>
    @endforeach

    </tbody>

    <tfoot>
        <tr>
            <td colspan="6">Einnahmen</td>
            <td class="text-right">
                {{ number_format($totalIncome, 2, ',', '.') }} €
            </td>
        </tr>

        <tr>
            <td colspan="6">Ausgaben</td>
            <td class="text-right">
                - {{ number_format($totalExpense, 2, ',', '.') }} €
            </td>
        </tr>

        <tr>
            <td colspan="6"><strong>Saldo</strong></td>
            <td class="text-right">
                <strong>{{ number_format($saldo, 2, ',', '.') }} €</strong>
            </td>
        </tr>
    </tfoot>
</table>

{{-- SEITENZAHLEN --}}
<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_text(
            700, 550,
            "Seite {PAGE_NUM} von {PAGE_COUNT}",
            null,
            8,
            array(0,0,0)
        );
    }
</script>

</body>
</html>
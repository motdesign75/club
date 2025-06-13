@extends('layouts.sidebar')

@section('title', 'Einnahmen & Ausgaben')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">📊 Einnahmen & Ausgaben</h1>

    <div class="bg-white p-6 rounded shadow space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div class="bg-green-100 text-green-800 p-4 rounded shadow">
                <p class="text-sm">Gesamte Einnahmen</p>
                <p class="text-xl font-semibold">
                    {{ number_format($summary['total_income'], 2, ',', '.') }} €
                </p>
            </div>
            <div class="bg-red-100 text-red-800 p-4 rounded shadow">
                <p class="text-sm">Gesamte Ausgaben</p>
                <p class="text-xl font-semibold">
                    {{ number_format($summary['total_expense'], 2, ',', '.') }} €
                </p>
            </div>
            <div class="bg-blue-100 text-blue-800 p-4 rounded shadow">
                <p class="text-sm">Saldo</p>
                <p class="text-xl font-semibold">
                    {{ number_format($summary['saldo'], 2, ',', '.') }} €
                </p>
            </div>
        </div>

        <div class="text-sm text-gray-600 mt-4">
            Zeitraum: {{ \Carbon\Carbon::parse($start)->format('d.m.Y') }} – {{ \Carbon\Carbon::parse($end)->format('d.m.Y') }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Einnahmen-Ausgaben-Rechnung')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-[#2954A3]">
                Einnahmen-Überschuss-Rechnung
            </h1>

            <p class="text-sm text-gray-500">
                Auswertung nach ideell / Zweckbetrieb / wirtschaftlich
            </p>
        </div>

    </div>


    {{-- FILTER BOX --}}
    <form class="bg-white shadow rounded-xl p-4 md:p-6 flex flex-col md:flex-row gap-4 md:items-end">

        <div class="flex flex-col w-full md:w-auto">
            <label class="text-sm text-gray-600">
                Von
            </label>

            <input
                type="date"
                name="start"
                value="{{ $start }}"
                class="border rounded-lg p-2 w-full"
            >
        </div>


        <div class="flex flex-col w-full md:w-auto">
            <label class="text-sm text-gray-600">
                Bis
            </label>

            <input
                type="date"
                name="end"
                value="{{ $end }}"
                class="border rounded-lg p-2 w-full"
            >
        </div>


        <button
            class="bg-[#2954A3] text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition"
        >
            Aktualisieren
        </button>

    </form>



    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-green-600 text-white rounded-xl p-6 shadow">
            <div class="text-sm opacity-80">
                Einnahmen gesamt
            </div>

            <div class="text-3xl font-bold mt-2">
                {{ number_format($totalIncome,2,',','.') }} €
            </div>
        </div>


        <div class="bg-red-600 text-white rounded-xl p-6 shadow">
            <div class="text-sm opacity-80">
                Ausgaben gesamt
            </div>

            <div class="text-3xl font-bold mt-2">
                {{ number_format($totalExpense,2,',','.') }} €
            </div>
        </div>


        <div class="bg-blue-600 text-white rounded-xl p-6 shadow">
            <div class="text-sm opacity-80">
                Saldo
            </div>

            <div class="text-3xl font-bold mt-2">
                {{ number_format($saldo,2,',','.') }} €
            </div>
        </div>

    </div>



    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block bg-white shadow rounded-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100 text-gray-700 text-sm">

                <tr>

                    <th class="p-3 text-left">
                        Bereich
                    </th>

                    <th class="p-3 text-right">
                        Einnahmen
                    </th>

                    <th class="p-3 text-right">
                        Ausgaben
                    </th>

                    <th class="p-3 text-right">
                        Saldo
                    </th>

                </tr>

            </thead>

            <tbody>

            @foreach($result as $area => $row)

                <tr class="border-t">

                    <td class="p-3 font-semibold">

                        {{ ucfirst($area) }}

                    </td>

                    <td class="p-3 text-right text-green-600 font-semibold">

                        {{ number_format($row['income'],2,',','.') }} €

                    </td>

                    <td class="p-3 text-right text-red-600 font-semibold">

                        {{ number_format($row['expense'],2,',','.') }} €

                    </td>

                    <td class="p-3 text-right font-bold">

                        {{ number_format($row['saldo'],2,',','.') }} €

                    </td>

                </tr>

            @endforeach


                <tr class="border-t bg-gray-50 font-bold">

                    <td class="p-3">
                        Gesamt
                    </td>

                    <td class="p-3 text-right text-green-700">
                        {{ number_format($totalIncome,2,',','.') }} €
                    </td>

                    <td class="p-3 text-right text-red-700">
                        {{ number_format($totalExpense,2,',','.') }} €
                    </td>

                    <td class="p-3 text-right">
                        {{ number_format($saldo,2,',','.') }} €
                    </td>

                </tr>

            </tbody>

        </table>

    </div>



    {{-- MOBILE CARDS --}}
    <div class="md:hidden space-y-4">

        @foreach($result as $area => $row)

            <div class="bg-white shadow rounded-xl p-4 space-y-2">

                <div class="font-bold text-[#2954A3]">
                    {{ ucfirst($area) }}
                </div>

                <div class="flex justify-between text-sm">
                    <span>Einnahmen</span>
                    <span class="text-green-600 font-semibold">
                        {{ number_format($row['income'],2,',','.') }} €
                    </span>
                </div>

                <div class="flex justify-between text-sm">
                    <span>Ausgaben</span>
                    <span class="text-red-600 font-semibold">
                        {{ number_format($row['expense'],2,',','.') }} €
                    </span>
                </div>

                <div class="flex justify-between text-sm font-bold">
                    <span>Saldo</span>
                    <span>
                        {{ number_format($row['saldo'],2,',','.') }} €
                    </span>
                </div>

            </div>

        @endforeach

    </div>


</div>

@endsection
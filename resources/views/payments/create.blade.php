@extends('layouts.app')

@section('content')

<div class="p-6 max-w-3xl mx-auto">

    <h1 class="text-xl font-semibold mb-4">
        Zahlung buchen
    </h1>


    <form method="POST"
          action="{{ route('payments.store', $invoice) }}">

        @csrf


        <div class="bg-white p-6 rounded shadow space-y-4">


            <div>
                Rechnung:
                <strong>{{ $invoice->invoice_number }}</strong>
            </div>


            <div>
                Betrag offen:
                <strong>
                    {{ number_format($invoice->getTotal(),2,',','.') }} €
                </strong>
            </div>


            <div>

                <label>Konto</label>

                <select name="account_id"
                        class="w-full border rounded p-2">

                    @foreach($accounts as $acc)

                        <option value="{{ $acc->id }}">
                            {{ $acc->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div>

                <label>Betrag</label>

                <input type="number"
                       step="0.01"
                       name="amount"
                       value="{{ $invoice->getTotal() }}"
                       class="w-full border rounded p-2">

            </div>


            <div>

                <label>Datum</label>

                <input type="date"
                       name="payment_date"
                       value="{{ date('Y-m-d') }}"
                       class="w-full border rounded p-2">

            </div>


            <div>

                <label>Notiz</label>

                <input type="text"
                       name="note"
                       class="w-full border rounded p-2">

            </div>


            <button class="bg-green-600 text-white px-4 py-2 rounded">

                Zahlung speichern

            </button>

        </div>

    </form>

</div>

@endsection
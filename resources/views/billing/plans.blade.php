@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Abo auswählen</h1>

    <div class="grid md:grid-cols-3 gap-6">
        @foreach($plans as $key => $plan)
            @php
                // erste Price-ID verwenden
                $priceId = $plan['stripe_price_ids'][0] ?? null;
                $name = $plan['name'] ?? ucfirst($key);
                $limit = $plan['member_limit'] ?? null;
            @endphp

            <div class="bg-white rounded-xl shadow p-5">
                <div class="text-lg font-semibold">{{ $name }}</div>

                <div class="text-sm text-gray-600 mt-1">
                    @if(!empty($limit))
                        Bis zu {{ $limit }} Mitglieder
                    @else
                        Unbegrenzte Mitglieder
                    @endif
                </div>

                @if($priceId)
                    <form method="POST" action="{{ route('billing.subscribe', ['priceId' => $priceId]) }}" class="mt-5">
                        @csrf
                        <button class="w-full py-2 rounded-lg bg-black text-white">
                            {{ $name }} wählen
                        </button>
                    </form>
                @else
                    <div class="mt-5 text-sm text-red-600">
                        Keine Stripe Price-ID hinterlegt.
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-8 text-sm text-gray-500">
        Du wirst zu Stripe weitergeleitet, um das Abo abzuschließen.
    </div>
</div>
@endsection

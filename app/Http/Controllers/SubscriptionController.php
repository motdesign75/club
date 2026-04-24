<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('subscription.index');
    }

    public function checkout(Request $request)
{
    $request->validate([
        'price_id' => 'required|string',
    ]);

    $tenant = Auth::user()->tenant;

    if (! $tenant) {
        abort(403);
    }

    // Stripe Kunde sicherstellen
    $tenant->createOrGetStripeCustomer();

    // 🔥 WICHTIG: Checkout mit Metadata
    return $tenant->newSubscription('default', $request->price_id)
        ->trialDays(7)
        ->checkout([
            'success_url' => route('dashboard'),
            'cancel_url' => route('subscription.index'),

            'metadata' => [
                'tenant_id' => $tenant->id, // 🔥 DAS IST DER SCHLÜSSEL
            ],
        ]);

    }
}
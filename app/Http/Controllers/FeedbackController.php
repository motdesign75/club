<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackSubmitted;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'category' => 'required|string|in:Fehler,Verbesserung,Allgemein',
            'view' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:2048',
        ]);

        $feedback = Feedback::create([
            'user_id' => auth()->id(),
            'category' => $request->input('category'),
            'view' => $request->input('view'),
            'url' => $request->input('url'),
            'message' => $request->input('message'),
        ]);

        // Mail versenden
        Mail::to('kontakt@clubano.de')->send(
            new FeedbackSubmitted($feedback)
        );

        return back()->with('success', 'Vielen Dank für dein Feedback!');
    }
}

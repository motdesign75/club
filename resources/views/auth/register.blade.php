@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <strong>Ups! Etwas stimmt nicht.</strong>
        <ul class="mt-2 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Registrieren</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-4">
                <label class="block">Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
    <label class="block">Vereinsname</label>
    <input type="text" name="verein" class="w-full border rounded p-2" required>
</div>
            <div class="mb-4">
                <label class="block">E-Mail</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Passwort</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Passwort bestätigen</label>
                <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Registrieren</button>
        </form>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Neuen Benutzer anlegen')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded shadow space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">👤 Neuen Benutzer anlegen</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input name="name" type="text" required class="w-full rounded border-gray-300 text-sm" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">E-Mail</label>
            <input name="email" type="email" required class="w-full rounded border-gray-300 text-sm" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Passwort</label>
            <input name="password" type="password" required class="w-full rounded border-gray-300 text-sm" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Passwort bestätigen</label>
            <input name="password_confirmation" type="password" required class="w-full rounded border-gray-300 text-sm" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Rolle</label>
            <select name="role" required class="w-full rounded border-gray-300 text-sm">
                <option value="SAdmin">Superadmin</option>
                <option value="Admin">Admin</option>
                <option value="User">Nutzer</option>
            </select>
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">💾 Speichern</button>
        </div>
    </form>
</div>
@endsection

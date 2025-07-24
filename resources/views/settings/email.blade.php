@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">SMTP-Einstellungen</h2>

    <form action="{{ route('settings.email.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Mailer</label>
            <input type="text" name="mail_mailer" value="{{ old('mail_mailer', $tenant->mail_mailer) }}" class="w-full border p-2 rounded" placeholder="smtp">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">SMTP Host</label>
            <input type="text" name="mail_host" value="{{ old('mail_host', $tenant->mail_host) }}" class="w-full border p-2 rounded" placeholder="smtp.example.com">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">SMTP Port</label>
            <input type="text" name="mail_port" value="{{ old('mail_port', $tenant->mail_port) }}" class="w-full border p-2 rounded" placeholder="587">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">SMTP Benutzername</label>
            <input type="text" name="mail_username" value="{{ old('mail_username', $tenant->mail_username) }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">SMTP Passwort</label>
            <input type="password" name="mail_password" value="{{ old('mail_password', $tenant->mail_password) }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Verschlüsselung</label>
            <input type="text" name="mail_encryption" value="{{ old('mail_encryption', $tenant->mail_encryption) }}" class="w-full border p-2 rounded" placeholder="tls">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Absender-E-Mail</label>
            <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $tenant->mail_from_address) }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Absender-Name</label>
            <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $tenant->mail_from_name) }}" class="w-full border p-2 rounded">
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">💾 Speichern</button>
        </div>
    </form>
</div>
@endsection

<div class="p-6 text-xl font-semibold text-indigo-700 border-b">
    🏛 Clubano beta
</div>

<nav class="p-4 text-sm text-indigo-800 space-y-6" aria-label="Hauptnavigation">
    <!-- Hauptnavigation -->
    <ul class="space-y-2">
        <li>
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('dashboard') ? 'bg-indigo-100 font-bold' : '' }}">
                🏠 Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('tenant.show') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('tenant.show') ? 'bg-indigo-100 font-bold' : '' }}">
                🏢 Verein
            </a>
        </li>
        <li>
            <a href="{{ route('members.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('members.index') ? 'bg-indigo-100 font-bold' : '' }}">
                👥 Mitglieder
            </a>
        </li>
    </ul>

    <hr class="border-indigo-100">

    <!-- Veranstaltungen -->
    <h2 class="text-xs uppercase text-indigo-400 pl-3">📅 Veranstaltungen</h2>
    <ul class="space-y-2">
        <li>
            <a href="{{ route('events.create') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('events.create') ? 'bg-indigo-100 font-bold' : '' }}">
                ➕ Neue Veranstaltung
            </a>
        </li>
        <li>
            <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('events.index') ? 'bg-indigo-100 font-bold' : '' }}">
                📆 Veranstaltungen anzeigen
            </a>
        </li>
    </ul>

    <hr class="border-indigo-100">

    <!-- Finanzen -->
    <h2 class="text-xs uppercase text-indigo-400 pl-3">💰 Finanzen</h2>
    <ul class="space-y-2">
        <li>
            <a href="{{ route('accounts.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('accounts.index') ? 'bg-indigo-100 font-bold' : '' }}">
                📒 Kontenplan
            </a>
        </li>
        <li>
            <a href="{{ route('transactions.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('transactions.*') ? 'bg-indigo-100 font-bold' : '' }}">
                📑 Buchungen
            </a>
        </li>
        <li>
            <a href="{{ route('transactions.summary') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('transactions.summary') ? 'bg-indigo-100 font-bold' : '' }}">
                📈 Einnahmen & Ausgaben
            </a>
        </li>
        <li>
            <a href="{{ route('invoices.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('invoices.*') ? 'bg-indigo-100 font-bold' : '' }}">
                🧾 Rechnungen
            </a>
        </li>
    </ul>

    <hr class="border-indigo-100">

    <!-- Dokumente -->
    <h2 class="text-xs uppercase text-indigo-400 pl-3">📁 Dokumente</h2>
    <ul class="space-y-2">
        <li>
            <a href="{{ route('protocols.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('protocols.index') ? 'bg-indigo-100 font-bold' : '' }}">
                📄 Protokolle
            </a>
        </li>
        <li>
            <a href="{{ route('protocols.create') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('protocols.create') ? 'bg-indigo-100 font-bold' : '' }}">
                ➕ Neues Protokoll
            </a>
        </li>
    </ul>

    <hr class="border-indigo-100">

    <!-- Einstellungen -->
    <h2 class="text-xs uppercase text-indigo-400 pl-3">⚙️ Einstellungen</h2>
    <ul class="space-y-2">
        <li>
            <a href="{{ route('memberships.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('memberships.index') ? 'bg-indigo-100 font-bold' : '' }}">
                💳 Mitgliedschaften
            </a>
        </li>
        <li>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('profile.edit') ? 'bg-indigo-100 font-bold' : '' }}">
                🙍 Profil
            </a>
        </li>
        <li>
            <a href="{{ route('import.mitglieder') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('import.mitglieder') ? 'bg-indigo-100 font-bold' : '' }}">
                📥 Mitgliederimport
            </a>
        </li>
        <li>
            <a href="{{ route('roles.edit') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('roles.edit') ? 'bg-indigo-100 font-bold' : '' }}">
                🔐 Rollen
            </a>
        </li>
        <li>
            <a href="{{ route('custom-fields.index') }}" class="block px-3 py-2 rounded hover:bg-indigo-100 {{ request()->routeIs('custom-fields.*') ? 'bg-indigo-100 font-bold' : '' }}">
                🧩 Eigene Felder
            </a>
        </li>
    </ul>

    <hr class="border-indigo-100">

    <!-- Logout -->
    @auth
        <form method="POST" action="{{ route('logout') }}" class="pt-4">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 hover:text-red-800 rounded">
                🚪 Logout
            </button>
        </form>
    @endauth
</nav>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Clubano')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Sidebar -->
        <aside class="bg-blue-50 w-full md:w-64 shadow-md" role="navigation" aria-label="Hauptmenü">
            <div class="p-6 text-xl font-semibold text-blue-900 border-b border-blue-100">
                🏛 Clubano
            </div>

            <nav class="p-4 text-sm text-blue-900">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="block px-3 py-2 rounded hover:bg-blue-100 transition focus:outline focus:ring-2 focus:ring-blue-400"
                           @if (request()->routeIs('dashboard')) aria-current="page" @endif>
                            🏠 Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenant.show') }}"
                           class="block px-3 py-2 rounded hover:bg-blue-100 transition focus:outline focus:ring-2 focus:ring-blue-400"
                           @if (request()->routeIs('tenant.show')) aria-current="page" @endif>
                            🏢 Verein
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('members.index') }}"
                           class="block px-3 py-2 rounded hover:bg-blue-100 transition focus:outline focus:ring-2 focus:ring-blue-400"
                           @if (request()->routeIs('members.index')) aria-current="page" @endif>
                            👥 Mitglieder
                        </a>
                    </li>
                </ul>

                <hr class="my-4 border-blue-100" aria-hidden="true">

                <h2 class="text-xs font-semibold uppercase text-blue-500 pl-3">💰 Finanzen</h2>
                <ul class="space-y-2 mt-1">
                    <li>
                        <a href="{{ route('accounts.index') }}"
                           class="block px-3 py-2 rounded hover:bg-blue-100 transition focus:outline focus:ring-2 focus:ring-blue-400"
                           @if (request()->routeIs('accounts.index')) aria-current="page" @endif>
                            📒 Kontenplan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.index') }}"
                           class="block px-3 py-2 rounded hover:bg-blue-100 transition focus:outline focus:ring-2 focus:ring-blue-400"
                           @if (request()->routeIs('transactions.*')) aria-current="page" @endif>
                            📑 Buchungen
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.summary') }}"
                           class="block px-3 py-2 rounded hover:bg-blue-100 transition focus:outline focus:ring-2 focus:ring-blue-400"
                           @if (request()->routeIs('transactions.summary')) aria-current="page" @endif>
                            📈 Einnahmen & Ausgaben
                        </a>
                    </li>
                </ul>

                <hr class="my-4 border-blue-100" aria-hidden="true">

                <h2 class="text-xs font-semibold uppercase text-blue-500 pl-3">⚙️ Einstellungen</h2>
                <ul class="space-y-2 mt-1">
                    <li>
                        <a href="{{ route('memberships.index') }}"
                           class="block px-3 py-2 rounded hover:bg-blue-100 transition focus:outline focus:ring-2 focus:ring-blue-400"
                           @if (request()->routeIs('memberships.index')) aria-current="page" @endif>
                            💳 Mitgliedschaften
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.edit') }}"
                           class="block px-3 py-2 rounded hover:bg-blue-100 transition focus:outline focus:ring-2 focus:ring-blue-400"
                           @if (request()->routeIs('profile.edit')) aria-current="page" @endif>
                            🙍 Profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('import.mitglieder') }}"
                           class="block px-3 py-2 rounded hover:bg-blue-100 transition focus:outline focus:ring-2 focus:ring-blue-400"
                           @if (request()->routeIs('import.mitglieder')) aria-current="page" @endif>
                            📥 Mitgliederimport
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('roles.edit') }}"
                           class="block px-3 py-2 rounded hover:bg-blue-100 transition focus:outline focus:ring-2 focus:ring-blue-400"
                           @if (request()->routeIs('roles.edit')) aria-current="page" @endif>
                            🔐 Rollen
                        </a>
                    </li>
                </ul>

                <hr class="my-4 border-blue-100" aria-hidden="true">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-3 py-2 rounded text-red-600 hover:bg-red-50 hover:text-red-800 transition focus:outline focus:ring-2 focus:ring-red-400">
                        🚪 Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>
</body>
</html>

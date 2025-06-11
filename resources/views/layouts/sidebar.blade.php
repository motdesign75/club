<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Clubano')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Sidebar mit pastellblauem Hintergrund -->
        <aside class="bg-blue-50 w-full md:w-64 shadow-md">
            <div class="p-6 text-xl font-semibold text-blue-900 border-b border-blue-100">
                🏛 Clubano
            </div>

            <nav class="p-4 space-y-2 text-sm text-blue-900">
                <a href="{{ route('dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-blue-100 transition">🏠 Dashboard</a>

                <a href="{{ route('tenant.show') }}"
                   class="block px-3 py-2 rounded hover:bg-blue-100 transition">🏢 Verein</a>

                <a href="{{ route('members.index') }}"
                   class="block px-3 py-2 rounded hover:bg-blue-100 transition">👥 Mitglieder</a>

                <hr class="my-3 border-blue-100">

                <div class="text-xs font-semibold uppercase text-blue-500 pl-3">⚙️ Einstellungen</div>

                <a href="{{ route('memberships.index') }}"
                   class="block px-3 py-2 rounded hover:bg-blue-100 transition">💳 Mitgliedschaften</a>

                <a href="{{ route('profile.edit') }}"
                   class="block px-3 py-2 rounded hover:bg-blue-100 transition">🙍 Profil</a>

                <hr class="my-3 border-blue-100">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-3 py-2 rounded text-red-600 hover:bg-red-50 hover:text-red-800 transition">
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

<!-- resources/views/layouts/sidebar.blade.php -->
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
        <aside class="bg-white w-full md:w-64 shadow-md">
            <div class="p-6 font-bold text-xl border-b">Clubano</div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-blue-600">🏠 Dashboard</a>
                <a href="{{ route('tenant.show') }}" class="block text-gray-700 hover:text-blue-600">🏢 Verein</a>
                <a href="{{ route('members.index') }}" class="block text-gray-700 hover:text-blue-600">👥 Mitglieder</a>
                <a href="{{ route('profile.edit') }}" class="block text-gray-700 hover:text-blue-600">⚙️ Profil</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button class="text-red-500 hover:text-red-700">🚪 Logout</button>
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

<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white px-4 py-12">
        <div class="w-full max-w-sm space-y-8">
            <!-- Logo -->
            <div class="flex justify-center">
                <img src="{{ asset('images/clubano-logo.svg') }}" alt="Clubano Logo" class="h-16 w-auto">
            </div>

            <!-- Headline -->
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900">Willkommen bei Clubano!</h1>
                <p class="mt-2 text-sm text-gray-600">Melden Sie sich mit Ihrer E-Mail-Adresse und Passwort an</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg p-3">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <input id="email" name="email" type="email" required autofocus placeholder="E-Mail-Adresse"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none placeholder-gray-400 text-sm"
                        value="{{ old('email') }}">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <input id="password" name="password" type="password" required placeholder="Passwort"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none placeholder-gray-400 text-sm">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <div class="text-right mt-2">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-black underline hover:text-blue-600">Passwort vergessen?</a>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-semibold shadow transition">
                        Weiter
                    </button>
                </div>
            </form>

            <!-- Oder mit Microsoft -->
            <div class="relative my-4">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Oder anmelden mit</span>
                </div>
            </div>

            <div>
                <a href="#" class="w-full flex items-center justify-center py-3 border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft" class="h-5 w-auto mr-2">
                    <span class="text-sm text-gray-700 font-medium">Microsoft</span>
                </a>
            </div>

            <!-- Registrierung -->
            <div class="text-center text-sm text-gray-600 mt-6">
                Noch kein Account?
                <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">Jetzt registrieren</a>
            </div>
        </div>
    </div>
</x-guest-layout>

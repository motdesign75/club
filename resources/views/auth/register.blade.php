<x-guest-layout>
    <div class="max-w-md mx-auto mt-12 bg-white p-8 rounded-2xl shadow-lg space-y-6 ring-1 ring-gray-200">
        <h1 class="text-2xl font-extrabold text-center text-gray-800">🔐 Registrierung</h1>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input id="name" name="name" type="text" required autofocus autocomplete="name"
                    value="{{ old('name') }}"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#2954A3] focus:outline-none" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-Mail-Adresse</label>
                <input id="email" name="email" type="email" required autocomplete="username"
                    value="{{ old('email') }}"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#2954A3] focus:outline-none" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Einladungscode -->
            <div>
                <label for="invite_code" class="block text-sm font-medium text-gray-700">Einladungscode</label>
                <input id="invite_code" name="invite_code" type="text" required
                    value="{{ old('invite_code') }}"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#2954A3] focus:outline-none" />
                <x-input-error :messages="$errors->get('invite_code')" class="mt-2" />
            </div>

            <!-- Passwort -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Passwort</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#2954A3] focus:outline-none" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Passwort bestätigen -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Passwort bestätigen</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#2954A3] focus:outline-none" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Buttons -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-2">
                <a href="{{ route('login') }}"
                   class="text-sm text-gray-600 hover:text-[#2954A3] underline">
                    Bereits registriert?
                </a>

                <button type="submit"
                        class="bg-[#2954A3] text-white px-6 py-2 rounded-xl shadow hover:bg-[#1E3F7F] transition-all">
                    ✅ Registrieren
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>

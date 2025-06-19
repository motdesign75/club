<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __('Mein Profil') }}
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-8">

            {{-- Abschnitt: Profilinformationen --}}
            <section class="bg-white p-6 rounded-lg shadow">
                <header class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Profilinformationen') }}</h3>
                    <p class="text-sm text-gray-600">Aktualisiere deinen Namen und deine E-Mail-Adresse.</p>
                </header>

                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    {{-- Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            :value="old('name', auth()->user()->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    {{-- E-Mail --}}
                    <div>
                        <x-input-label for="email" :value="__('E-Mail-Adresse')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                            :value="old('email', auth()->user()->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                            <div class="mt-2 text-sm text-gray-600">
                                <p>{{ __('Deine E-Mail-Adresse ist nicht verifiziert.') }}</p>
                                <button form="send-verification"
                                    class="underline text-sm text-indigo-600 hover:text-indigo-800 focus:outline-none">
                                    {{ __('Hier klicken, um die Verifizierung erneut zu senden.') }}
                                </button>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 text-green-600 font-medium">
                                        {{ __('Ein neuer Verifizierungslink wurde an deine E-Mail-Adresse gesendet.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Speichern --}}
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Speichern') }}</x-primary-button>

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600">{{ __('Gespeichert.') }}</p>
                        @endif
                    </div>
                </form>
            </section>

            {{-- Abschnitt: Passwort ändern --}}
            <section class="bg-white p-6 rounded-lg shadow">
                <header class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Passwort ändern') }}</h3>
                    <p class="text-sm text-gray-600">Vergib ein sicheres neues Passwort für deinen Zugang.</p>
                </header>

                @include('profile.partials.update-password-form')
            </section>

            {{-- Abschnitt: Account löschen --}}
            <section class="bg-white p-6 rounded-lg shadow">
                <header class="mb-6">
                    <h3 class="text-lg font-medium text-red-600">{{ __('Account löschen') }}</h3>
                    <p class="text-sm text-gray-600">Achtung: Dieser Vorgang ist unwiderruflich!</p>
                </header>

                @include('profile.partials.delete-user-form')
            </section>

        </div>
    </div>
</x-app-layout>

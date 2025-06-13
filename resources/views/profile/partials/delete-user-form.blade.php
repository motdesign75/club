<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Konto löschen
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Sobald dein Konto gelöscht wurde, werden alle zugehörigen Daten dauerhaft entfernt. Bitte lade zuvor alle Daten herunter, die du behalten möchtest.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        Konto löschen
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                Bist du sicher, dass du dein Konto löschen möchtest?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Sobald dein Konto gelöscht wurde, werden alle zugehörigen Ressourcen und Daten dauerhaft gelöscht. Bitte gib dein Passwort ein, um die Löschung zu bestätigen.
            </p>

            <div class="mt-6">
                <x-input-label for="delete_account_password" value="Passwort" class="sr-only" />

                <x-text-input
                    id="delete_account_password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Passwort"
                />

                <x-input-error for="password" class="mt-2" :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Abbrechen
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Konto endgültig löschen
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

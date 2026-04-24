<!-- Sidebar Header mit Wow-Effekt -->
<div class="sticky top-0 z-30 bg-gradient-to-r from-indigo-600 via-indigo-500 to-indigo-400 p-6 text-xl font-semibold text-white shadow-inner">
    <div class="flex items-center gap-2">
        <x-heroicon-o-fire class="h-6 w-6 text-white" />
        <span>Clubano beta</span>
    </div>
</div>

<!-- Sidebar Navigation -->
<nav class="p-4 text-sm text-indigo-900 space-y-6" aria-label="Hauptnavigation">

    <!-- Hauptnavigation -->
    <div>
        <h2 class="sr-only">Hauptnavigation</h2>

        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-indigo-100 to-indigo-200 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-home class="h-5 w-5 text-indigo-500" />
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('tenant.show') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('tenant.show') ? 'bg-gradient-to-r from-indigo-100 to-indigo-200 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-building-office class="h-5 w-5 text-indigo-500" />
                    <span>Verein</span>
                </a>
            </li>

            <li>
                <a href="{{ route('members.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('members.*') ? 'bg-gradient-to-r from-indigo-100 to-indigo-200 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-users class="h-5 w-5 text-indigo-500" />
                    <span>Mitglieder</span>
                </a>
            </li>

            <li>
                <a href="{{ route('contacts.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('contacts.*') ? 'bg-gradient-to-r from-indigo-100 to-indigo-200 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-identification class="h-5 w-5 text-indigo-500" />
                    <span>Kontakte</span>
                </a>
            </li>

            <li>
                <a href="{{ route('projects.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('projects.*') ? 'bg-gradient-to-r from-indigo-100 to-indigo-200 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-rectangle-stack class="h-5 w-5 text-indigo-500" />
                    <span>Projekte</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Veranstaltungen -->
    <div>
        <h2 class="pl-3 pt-4 text-xs uppercase text-indigo-400">Veranstaltungen</h2>

        <ul class="mt-2 space-y-2">
            <li>
                <a href="{{ route('events.create') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('events.create') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-plus-circle class="h-5 w-5 text-indigo-500" />
                    <span>Neue Veranstaltung</span>
                </a>
            </li>

            <li>
                <a href="{{ route('events.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('events.index') || request()->routeIs('events.show') || request()->routeIs('events.edit') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-calendar class="h-5 w-5 text-indigo-500" />
                    <span>Veranstaltungen anzeigen</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Finanzen -->
    <div>
        <h2 class="pl-3 pt-4 text-xs uppercase text-indigo-400">Finanzen</h2>

        <ul class="mt-2 space-y-2">
            <li>
                <a href="{{ route('accounts.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('accounts.*') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-indigo-500" />
                    <span>Konten</span>
                </a>
            </li>

            <li>
                <a href="{{ route('transactions.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('transactions.index') || request()->routeIs('transactions.create') || request()->routeIs('transactions.edit') || request()->routeIs('transactions.show') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-document-text class="h-5 w-5 text-indigo-500" />
                    <span>Buchungen</span>
                </a>
            </li>

            <li>
                <a href="{{ route('transactions.summary') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('transactions.summary') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-chart-bar class="h-5 w-5 text-indigo-500" />
                    <span>Einnahmen & Ausgaben</span>
                </a>
            </li>

            <li>
                <a href="{{ route('transactions.eur') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('transactions.eur') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-document-duplicate class="h-5 w-5 text-indigo-500" />
                    <span>EüR-Auswertung</span>
                </a>
            </li>

            <li>
                <a href="{{ route('invoices.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('invoices.*') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-receipt-percent class="h-5 w-5 text-indigo-500" />
                    <span>Rechnungen</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Dokumente -->
    <div>
        <h2 class="pl-3 pt-4 text-xs uppercase text-indigo-400">Dokumente</h2>

        <ul class="mt-2 space-y-2">
            <li>
                <a href="{{ route('protocols.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('protocols.index') || request()->routeIs('protocols.show') || request()->routeIs('protocols.edit') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-document-duplicate class="h-5 w-5 text-indigo-500" />
                    <span>Protokolle</span>
                </a>
            </li>

            <li>
                <a href="{{ route('protocols.create') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('protocols.create') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-plus class="h-5 w-5 text-indigo-500" />
                    <span>Neues Protokoll</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Dokumenten-Vorlagen -->
    <div>
        <h2 class="pl-3 pt-4 text-xs uppercase text-indigo-400">Dokumenten-Vorlagen</h2>

        <ul class="mt-2 space-y-2">
            <li>
                <a href="{{ route('templates.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('templates.*') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-document class="h-5 w-5 text-indigo-500" />
                    <span>Vorlagen</span>
                </a>
            </li>

            <li>
                <a href="{{ route('mail.create') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('mail.create') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-paper-airplane class="h-5 w-5 text-indigo-500" />
                    <span>Vorlagen versenden</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Einstellungen -->
    <div>
        <h2 class="pl-3 pt-4 text-xs uppercase text-indigo-400">Einstellungen</h2>

        <ul class="mt-2 space-y-2">
            <li>
                <a href="{{ route('memberships.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('memberships.*') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-credit-card class="h-5 w-5 text-indigo-500" />
                    <span>Mitgliedschaften</span>
                </a>
            </li>

            <li>
                <a href="{{ route('tags.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('tags.*') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-tag class="h-5 w-5 text-indigo-500" />
                    <span>Tags verwalten</span>
                </a>
            </li>

            <li>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('profile.edit') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-user class="h-5 w-5 text-indigo-500" />
                    <span>Profil</span>
                </a>
            </li>

            <li>
                <a href="{{ route('import.mitglieder') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('import.mitglieder') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-cloud-arrow-down class="h-5 w-5 text-indigo-500" />
                    <span>Mitgliederimport</span>
                </a>
            </li>

            <li>
                <a href="{{ route('roles.edit') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('roles.edit') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-lock-closed class="h-5 w-5 text-indigo-500" />
                    <span>Rollen</span>
                </a>
            </li>

            <li>
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-users class="h-5 w-5 text-indigo-500" />
                    <span>Benutzer</span>
                </a>
            </li>

            <li>
                <a href="{{ route('custom-fields.index') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->routeIs('custom-fields.*') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-puzzle-piece class="h-5 w-5 text-indigo-500" />
                    <span>Eigene Felder</span>
                </a>
            </li>

            <li>
                <a href="{{ url('/settings/email') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2 transition-all duration-150 {{ request()->is('settings/email') ? 'bg-indigo-100 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                    <x-heroicon-o-envelope class="h-5 w-5 text-indigo-500" />
                    <span>Maileinstellungen</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Logout -->
    @auth
        <form method="POST" action="{{ route('logout') }}" class="pt-4">
            @csrf

            <button type="submit"
                    class="flex w-full items-center gap-2 rounded px-3 py-2 text-left text-red-600 transition-all duration-150 hover:bg-red-50 hover:text-red-800">
                <x-heroicon-o-arrow-left-on-rectangle class="h-5 w-5 text-red-500" />
                <span>Logout</span>
            </button>
        </form>
    @endauth
</nav>

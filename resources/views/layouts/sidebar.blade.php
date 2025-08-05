<!-- Sidebar Header mit Wow-Effekt -->
<div class="p-6 text-xl font-semibold text-white bg-gradient-to-r from-indigo-600 via-indigo-500 to-indigo-400 shadow-inner sticky top-0 z-30">
    <div class="flex items-center gap-2">
        <x-heroicon-o-fire class="h-6 w-6 text-white" />
        Clubano beta
    </div>
</div>

<!-- Sidebar Navigation -->
<nav class="p-4 text-sm text-indigo-900 space-y-6" aria-label="Hauptnavigation">
    <!-- Hauptnavigation -->
    <ul class="space-y-2">
        <li>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-indigo-100 to-indigo-200 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                <x-heroicon-o-home class="h-5 w-5 text-indigo-500"/>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('tenant.show') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-150 {{ request()->routeIs('tenant.show') ? 'bg-gradient-to-r from-indigo-100 to-indigo-200 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                <x-heroicon-o-building-office class="h-5 w-5 text-indigo-500"/>
                <span>Verein</span>
            </a>
        </li>
        <li>
            <a href="{{ route('members.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-150 {{ request()->routeIs('members.index') ? 'bg-gradient-to-r from-indigo-100 to-indigo-200 font-bold shadow-inner' : 'hover:bg-indigo-50' }}">
                <x-heroicon-o-users class="h-5 w-5 text-indigo-500"/>
                <span>Mitglieder</span>
            </a>
        </li>
    </ul>

    <!-- Veranstaltungen -->
    <h2 class="text-xs uppercase text-indigo-400 pl-3 pt-4">Veranstaltungen</h2>
    <ul class="space-y-2">
        <li>
            <a href="{{ route('events.create') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('events.create') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-plus-circle class="h-5 w-5 text-indigo-500"/>
                <span>Neue Veranstaltung</span>
            </a>
        </li>
        <li>
            <a href="{{ route('events.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('events.index') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-calendar class="h-5 w-5 text-indigo-500"/>
                <span>Veranstaltungen anzeigen</span>
            </a>
        </li>
    </ul>

    <!-- Finanzen -->
    <h2 class="text-xs uppercase text-indigo-400 pl-3 pt-4">Finanzen</h2>
    <ul class="space-y-2">
        <li>
            <a href="{{ route('accounts.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('accounts.index') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-indigo-500"/>
                <span>Konten</span>
            </a>
        </li>
        <li>
            <a href="{{ route('transactions.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('transactions.*') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-document-text class="h-5 w-5 text-indigo-500"/>
                <span>Buchungen</span>
            </a>
        </li>
        <li>
            <a href="{{ route('transactions.summary') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('transactions.summary') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-chart-bar class="h-5 w-5 text-indigo-500"/>
                <span>Einnahmen & Ausgaben</span>
            </a>
        </li>
        <li>
            <a href="{{ route('invoices.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('invoices.*') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-receipt-percent class="h-5 w-5 text-indigo-500"/>
                <span>Rechnungen</span>
            </a>
        </li>
    </ul>

    <!-- Dokumente -->
    <h2 class="text-xs uppercase text-indigo-400 pl-3 pt-4">Dokumente</h2>
    <ul class="space-y-2">
        <li>
            <a href="{{ route('protocols.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('protocols.index') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-document-duplicate class="h-5 w-5 text-indigo-500"/>
                <span>Protokolle</span>
            </a>
        </li>
        <li>
            <a href="{{ route('protocols.create') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('protocols.create') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-plus class="h-5 w-5 text-indigo-500"/>
                <span>Neues Protokoll</span>
            </a>
        </li>
    </ul>

    <!-- Einstellungen -->
    <h2 class="text-xs uppercase text-indigo-400 pl-3 pt-4">Einstellungen</h2>
    <ul class="space-y-2">
        <li>
            <a href="{{ route('memberships.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('memberships.index') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-credit-card class="h-5 w-5 text-indigo-500"/>
                <span>Mitgliedschaften</span>
            </a>
        </li>
        <li>
            <a href="{{ route('tags.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('tags.*') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-tag class="h-5 w-5 text-indigo-500"/>
                <span>Tags verwalten</span>
            </a>
        </li>
        <li>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('profile.edit') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-user class="h-5 w-5 text-indigo-500"/>
                <span>Profil</span>
            </a>
        </li>
        <li>
            <a href="{{ route('import.mitglieder') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('import.mitglieder') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-cloud-arrow-down class="h-5 w-5 text-indigo-500"/>
                <span>Mitgliederimport</span>
            </a>
        </li>
        <li>
            <a href="{{ route('roles.edit') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('roles.edit') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-lock-closed class="h-5 w-5 text-indigo-500"/>
                <span>Rollen</span>
            </a>
        </li>
        <li>
            <a href="{{ route('custom-fields.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->routeIs('custom-fields.*') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-puzzle-piece class="h-5 w-5 text-indigo-500"/>
                <span>Eigene Felder</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/settings/email') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 transition-all duration-150 {{ request()->is('settings/email') ? 'bg-indigo-100 font-bold shadow-inner' : '' }}">
                <x-heroicon-o-envelope class="h-5 w-5 text-indigo-500"/>
                <span>Maileinstellungen</span>
            </a>
        </li>
    </ul>

    <!-- Logout -->
    @auth
        <form method="POST" action="{{ route('logout') }}" class="pt-4">
            @csrf
            <button type="submit"
                    class="flex items-center gap-2 w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 hover:text-red-800 rounded transition-all duration-150">
                <x-heroicon-o-arrow-left-on-rectangle class="h-5 w-5 text-red-500"/>
                <span>Logout</span>
            </button>
        </form>
    @endauth
</nav>

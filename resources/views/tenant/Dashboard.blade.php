<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-extrabold text-[#2954A3]">
            🏢 Vereinsprofil
        </h1>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8 ring-1 ring-gray-200 space-y-8 text-gray-800">

            {{-- Vereinslogo --}}
            @if ($tenant->logo)
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $tenant->logo) }}"
                         alt="Logo des Vereins {{ $tenant->name }}"
                         class="h-28 object-contain rounded shadow">
                </div>
            @endif

            {{-- Informationen --}}
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-semibold text-gray-600">📛 Vereinsname</dt>
                    <dd class="text-lg">{{ $tenant->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-600">🔗 Slug</dt>
                    <dd class="text-lg">{{ $tenant->slug }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-600">📧 E-Mail</dt>
                    <dd class="text-lg">
                        <a href="mailto:{{ $tenant->email }}" class="hover:underline text-[#2954A3]">
                            {{ $tenant->email }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-600">📞 Telefon</dt>
                    <dd class="text-lg">
                        <a href="tel:{{ $tenant->phone }}" class="hover:underline text-[#2954A3]">
                            {{ $tenant->phone }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-600">📍 Adresse</dt>
                    <dd class="text-lg">{{ $tenant->address }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-600">🏙️ PLZ / Ort</dt>
                    <dd class="text-lg">{{ $tenant->zip }} {{ $tenant->city }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-600">🧾 Registernummer</dt>
                    <dd class="text-lg">{{ $tenant->register_number }}</dd>
                </div>
            </dl>

            {{-- Button --}}
            <div class="text-right pt-4">
                <a href="{{ route('tenant.edit') }}"
                   class="inline-block bg-[#2954A3] hover:bg-[#1E3F7F] text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all"
                   aria-label="Vereinsdaten bearbeiten">
                    ✏️ Vereinsdaten bearbeiten
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

<div>
    <div class="flex space-x-4 border-b mb-6">
        @foreach (['entries' => '🎉 Eintritte', 'exits' => '😢 Austritte', 'birthdays' => '🎂 Geburtstage', 'anniversaries' => '🏅 Jubiläen'] as $key => $label)
            <button wire:click="setTab('{{ $key }}')"
                    class="pb-2"
                    @class([
                        'border-b-2 font-semibold text-blue-600' => $tab === $key,
                        'text-gray-500' => $tab !== $key
                    ])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if ($tab === 'entries')
        @forelse ($entries as $member)
            <div class="py-2 border-b">{{ $member->full_name }} – Eintritt: {{ $member->entry_date->format('d.m.Y') }}</div>
        @empty
            <p class="text-gray-500">Keine Eintritte im aktuellen Monat.</p>
        @endforelse
    @elseif ($tab === 'exits')
        @forelse ($exits as $member)
            <div class="py-2 border-b">{{ $member->full_name }} – Austritt: {{ $member->exit_date->format('d.m.Y') }}</div>
        @empty
            <p class="text-gray-500">Keine Austritte im aktuellen Monat.</p>
        @endforelse
    @elseif ($tab === 'birthdays')
        @forelse ($birthdays->sortBy(fn($m) => $m->birthday->day) as $member)
            @php $nextAge = $member->birthday->age + 1; @endphp
            <div class="py-2 border-b">
                {{ $member->full_name }} – {{ $member->birthday->format('d.m.') }} (wird {{ $nextAge }}{{ $nextAge % 10 === 0 ? ' 🎉' : '' }})
            </div>
        @empty
            <p class="text-gray-500">Keine Geburtstage im aktuellen Monat.</p>
        @endforelse
    @elseif ($tab === 'anniversaries')
        @forelse ($anniversaries as $member)
            <div class="py-2 border-b">{{ $member->full_name }} – {{ now()->year - $member->entry_date->year }} Jahre Mitglied</div>
        @empty
            <p class="text-gray-500">Keine Jubiläen heute.</p>
        @endforelse
    @endif
</div>

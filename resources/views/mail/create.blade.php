@extends('layouts.app')

@section('content')

<div class="p-6 max-w-6xl mx-auto">

<h1 class="text-2xl font-bold text-gray-800 mb-6">
Serienmail senden
</h1>


@if(session('success'))
<div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
    {{ session('success') }}
</div>
@endif



<form method="POST"
      action="{{ route('mail.send') }}">

@csrf



<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">



{{-- ================= LEFT ================= --}}

<div class="space-y-5">


{{-- TEMPLATE --}}

<div class="bg-white border rounded-lg p-4">

<label class="text-sm font-semibold">
Vorlage
</label>

<select name="template_id"
        class="w-full border rounded p-2 mt-1">

@foreach($templates as $t)

<option value="{{ $t->id }}">
{{ $t->name }}
</option>

@endforeach

</select>

</div>



{{-- ACTIONS --}}

<div class="bg-white border rounded-lg p-4 space-y-3">

<div class="text-sm font-semibold">
Auswahl
</div>


<div class="flex gap-2">

<button type="button"
        onclick="selectAll()"
        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">

Alle

</button>


<button type="button"
        onclick="unselectAll()"
        class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">

Keine

</button>

</div>


<div class="text-sm text-gray-600">

Ausgewählt:
<span id="memberCount"
      class="font-semibold text-blue-600">
0
</span>

</div>


<button
class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">

Serienmail senden

</button>

</div>



</div>



{{-- ================= RIGHT ================= --}}

<div>


<div class="bg-white border rounded-lg p-4">


<div class="flex justify-between items-center mb-2">

<div class="font-semibold text-gray-700">
Mitglieder auswählen
</div>

<input type="text"
       id="memberSearch"
       placeholder="Suchen…"
       class="border rounded p-1 text-sm"
       onkeyup="filterMembers()">

</div>



<div class="border rounded overflow-hidden">

<div class="max-h-[350px] overflow-y-auto">

<table class="w-full text-sm">

<thead class="bg-gray-50 sticky top-0">

<tr>

<th class="p-2 w-8"></th>

<th class="p-2 text-left">
Name
</th>

<th class="p-2 text-left">
Email
</th>

</tr>

</thead>



<tbody id="memberTable">

@foreach($members as $m)

<tr class="border-t memberRow hover:bg-gray-50">

<td class="p-2">

<input type="checkbox"
       class="memberCheckbox"
       name="members[]"
       value="{{ $m->id }}"
       onchange="updateCount()">

</td>

<td class="p-2">

{{ $m->first_name }}
{{ $m->last_name }}

</td>

<td class="p-2 text-gray-600">

{{ $m->email }}

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>


</div>


</div>



</div>

</form>

</div>




<script>

function selectAll()
{
    document
        .querySelectorAll('.memberCheckbox')
        .forEach(el => el.checked = true);

    updateCount();
}


function unselectAll()
{
    document
        .querySelectorAll('.memberCheckbox')
        .forEach(el => el.checked = false);

    updateCount();
}


function updateCount()
{
    let count =
        document
        .querySelectorAll('.memberCheckbox:checked')
        .length;

    document
        .getElementById('memberCount')
        .innerText = count;
}


function filterMembers()
{
    let search =
        document
        .getElementById('memberSearch')
        .value
        .toLowerCase();

    document
        .querySelectorAll('.memberRow')
        .forEach(row => {

            let text =
                row.innerText.toLowerCase();

            row.style.display =
                text.includes(search)
                ? ''
                : 'none';

        });

}

</script>


@endsection
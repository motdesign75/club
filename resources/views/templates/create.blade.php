@extends('layouts.app')

@section('content')

<div class="p-6">

<h1 class="text-xl font-semibold mb-4">
Neue Vorlage
</h1>


<form id="templateForm"
method="POST"
action="{{ route('templates.store') }}">

@csrf


<div class="bg-white rounded shadow p-6 space-y-4">


<div>
<label>Name</label>

<input
type="text"
name="name"
class="w-full border rounded p-2"
required>
</div>


<div>
<label>Typ</label>

<select
name="type"
class="w-full border rounded p-2">

<option value="mail">Mail</option>
<option value="letter">Brief</option>
<option value="pdf">PDF</option>

</select>
</div>


<div>
<label>Betreff</label>

<input
type="text"
name="subject"
class="w-full border rounded p-2">
</div>


<div>
<label>Text</label>

@include('templates.partials.placeholders')

<textarea
id="body"
name="body"
class="w-full border rounded p-2"
rows="6"></textarea>

</div>


<button
type="submit"
class="bg-green-600 text-white px-4 py-2 rounded">

Speichern

</button>


</div>

</form>

</div>

@endsection



@push('scripts')

<script src="/tinymce/tinymce.min.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

tinymce.init({

selector: '#body',

license_key: 'gpl',

height: 400,

plugins: 'lists link image table code fullscreen',

toolbar:
'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',

});



const form = document.getElementById("templateForm");

form.addEventListener("submit", function () {

tinymce.triggerSave();

});

});

</script>

@endpush

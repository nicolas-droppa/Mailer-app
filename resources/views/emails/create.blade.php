@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto py-8">
  <h2 class="text-2xl font-bold text-cyan-600 mb-4">Odoslať e-mail</h2>
  <form action="{{ route('emails.send') }}" method="POST" class="space-y-4">
    @csrf

    <div class="w-full md:w-1/2 lg:w-1/4">
      <label class="block text-sm font-medium">Šablóna</label>
      <select name="template_id" class="mt-1 w-full border rounded px-2 py-1">
        @foreach($templates as $t)
          <option value="{{ $t->id }}">{{ $t->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="w-full md:w-1/2 lg:w-1/4">
      <label class="block text-sm font-medium">Príjemcovia</label>
      <select id="recipients" name="contact_ids[]" multiple class="mt-1 w-full border rounded px-2 py-1 h-32">
        @foreach($contacts as $c)
          <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email }})</option>
        @endforeach
      </select>
    </div>

    <div class="w-full md:w-1/2 lg:w-1/4">
      <button type="submit"
              class="w-full py-2 px-4 bg-cyan-500 text-white rounded hover:bg-cyan-600 transition">
        Odoslať
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
  new TomSelect("#recipients", {
    plugins: ['remove_button'],
    placeholder: 'Vyberte príjemcov...'
  });
</script>
@endpush
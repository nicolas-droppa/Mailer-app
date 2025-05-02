@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto py-8">
  <h2 class="text-3xl font-semibold text-cyan-700 mb-6">Odoslať e-mail</h2>

  <form action="{{ route('emails.send') }}" method="POST" class="bg-white p-6 rounded shadow-md space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <!-- Šablóna -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Šablóna</label>
        <select name="template_id" class="w-full border-gray-300 rounded px-3 py-2">
          @foreach($templates as $t)
            <option value="{{ $t->id }}">{{ $t->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Príjemcovia -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Príjemcovia</label>
        <select id="recipients" name="contact_ids[]" multiple class="w-full border-gray-300 rounded px-3 py-2 h-40">
          @foreach($contacts as $c)
            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email }})</option>
          @endforeach
        </select>
      </div>

      <!-- Odoslanie hneď / v budúcnosti -->
      <div class="mt-4">
        <label class="block text-sm font-medium mb-1">Kedy odoslať?</label>
        <select name="send_option" class="w-full border rounded px-2 py-1">
          <option value="now" selected>Odoslať hneď</option>
          <option value="later">Odoslať neskôr</option>
        </select>
      </div>
    </div>

    <!-- Tlačidlo Odoslať -->
    <div class="text-right">
      <button type="submit"
              class="inline-block px-6 py-2 bg-cyan-600 text-white rounded hover:bg-cyan-700 transition">
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

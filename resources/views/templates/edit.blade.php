@extends('layouts.app')

@section('content')
<div class="flex justify-center py-12">
  <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-bold text-cyan-600 mb-4">Upraviť šablónu</h2>

    <form action="{{ route('templates.update', $template) }}" method="POST" class="space-y-4">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm font-medium text-gray-700">Názov</label>
        <input name="name" value="{{ old('name', $template->name) }}"
               class="w-full border-gray-300 rounded-md px-2 py-1 @error('name') border-red-500 @enderror">
        @error('name')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Predmet</label>
        <input name="subject" value="{{ old('subject', $template->subject) }}"
               class="w-full border-gray-300 rounded-md px-2 py-1 @error('subject') border-red-500 @enderror">
        @error('subject')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Telo</label>
        <textarea name="body" rows="5"
                  class="w-full border-gray-300 rounded-md px-2 py-1 @error('body') border-red-500 @enderror">{{ old('body', $template->body) }}</textarea>
        @error('body')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
      </div>

      <button type="submit"
              class="bg-cyan-500 text-white px-4 py-2 rounded hover:bg-cyan-600 transition">
        Aktualizovať
      </button>

      <a href="{{ route('templates.index') }}" class="text-sm text-gray-600 hover:underline">
        Späť na zoznam šablón
      </a>
    </form>
  </div>
</div>
@endsection
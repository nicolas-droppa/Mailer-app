@extends('layouts.app')
@section('content')
<div class="flex justify-center py-12">
  <div class="w-full max-w-lg bg-white p-6 rounded shadow">
    <h3 class="text-xl font-bold text-cyan-600 mb-4">Nová šablóna</h3>
    <form action="{{ route('templates.store') }}" method="POST" class="space-y-4">
      @csrf
      <div><label class="block">Názov</label><input name="name" class="w-full border rounded px-2" value="{{ old('name') }}">@error('name')<p class="text-red-500">{{ $message }}</p>@enderror</div>
      <div><label class="block">Predmet</label><input name="subject" class="w-full border rounded px-2" value="{{ old('subject') }}">@error('subject')<p class="text-red-500">{{ $message }}</p>@enderror</div>
      <div><label class="block">Telo</label><textarea name="body" rows="5" class="w-full border rounded px-2">{{ old('body') }}</textarea>@error('body')<p class="text-red-500">{{ $message }}</p>@enderror</div>
      <button type="submit" class="bg-cyan-500 text-white px-4 py-2 rounded hover:bg-cyan-600">Uložiť</button>
    </form>
  </div>
</div>
@endsection
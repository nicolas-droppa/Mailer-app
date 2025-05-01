@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-cyan-600">História e-mailov</h2>
    <a href="{{ route('emails.create') }}" class="bg-cyan-500 text-white px-4 py-2 rounded hover:bg-cyan-600 transition">
      Nový e-mail
    </a>
  </div>
  <div class="bg-white p-4 rounded shadow">
    <table class="min-w-full">
      <thead>
        <tr>
          <th class="px-4 py-2 text-left">Predmet</th>
          <th class="px-4 py-2 text-left">Príjemcovia</th>
          <th class="px-4 py-2 text-left">Dátum</th>
        </tr>
      </thead>
      <tbody>
        @foreach($history as $m)
          <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-2">{{ $m->subject }}</td>
            <td class="px-4 py-2">{{ implode(', ', $m->recipients) }}</td>
            <td class="px-4 py-2">{{ $m->created_at->format('d.m.Y H:i') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

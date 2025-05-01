@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
  <h2 class="text-2xl font-bold mb-4 text-cyan-600">Šablóny</h2>
  <a href="{{ route('templates.create') }}" class="bg-cyan-500 text-white px-4 py-2 rounded hover:bg-cyan-600">Nová šablóna</a>
  <div class="mt-4 bg-white p-4 rounded shadow">
    <table class="min-w-full">
      <thead><tr><th>Name</th><th>Subject</th><th>Akcie</th></tr></thead>
      <tbody>@foreach($templates as $t)<tr class="border-b hover:bg-gray-50">
        <td class="px-3 py-2">{{ $t->name }}</td>
        <td class="px-3 py-2">{{ $t->subject }}</td>
        <td class="px-3 py-2">
          <a href="{{ route('templates.edit',$t) }}" class="text-blue-600">Upraviť</a>
          <form action="{{ route('templates.destroy',$t) }}" method="POST" class="inline">@csrf @method('DELETE')
            <button type="submit" class="text-red-600 ml-2">Zmazať</button>
          </form>
        </td>
      </tr>@endforeach</tbody>
    </table>
  </div>
</div>
@endsection
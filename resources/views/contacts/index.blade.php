@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Kontakty</h1>
        <a href="{{ route('contacts.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Pridať kontakt</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Meno</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Oslovenie</th>
                <th class="border px-4 py-2">Pohlavie</th>
                <th class="border px-4 py-2">Akcie</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
                <tr>
                    <td class="border px-4 py-2">{{ $contact->name }}</td>
                    <td class="border px-4 py-2">{{ $contact->email }}</td>
                    <td class="border px-4 py-2 capitalize">{{ $contact->salutation }}</td>
                    <td class="border px-4 py-2 capitalize">{{ $contact->gender }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('contacts.edit', $contact) }}" class="text-blue-600 mr-2">Upraviť</a>
                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Naozaj zmazať?')" class="text-red-600">Zmazať</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

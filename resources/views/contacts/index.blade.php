@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-semibold mb-4">Kontakty</h2>

    <div class="mb-4">
        <a href="{{ route('contacts.create') }}" class="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
            Pridať nový kontakt
        </a>
    </div>

    <div class="bg-white p-6 rounded shadow-md">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left">Meno</th>
                    <th class="py-2 px-4 text-left">E-mail</th>
                    <th class="py-2 px-4 text-left">Oslovenie</th>
                    <th class="py-2 px-4 text-left">Pohlavie</th>
                    <th class="py-2 px-4 text-left">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-2 px-4">{{ $contact->name }}</td>
                        <td class="py-2 px-4">{{ $contact->email }}</td>
                        <td class="py-2 px-4">{{ ucfirst($contact->salutation) }}</td>
                        <td class="py-2 px-4">{{ ucfirst($contact->gender) }}</td>
                        <td class="py-2 px-4">
                            <a href="{{ route('contacts.edit', $contact->id) }}" class="text-blue-500 hover:text-blue-700">Upravit</a>
                            <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="inline ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Zmazať</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
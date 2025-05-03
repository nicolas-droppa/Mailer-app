@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="contactFilter()">
    <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2 text-gray-800">
        <i class="fas fa-user text-blue-500"></i>
        Kontakty
    </h2>

    <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <a href="{{ route('contacts.create') }}" class="inline-flex items-center gap-2 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
            <i class="fas fa-user-plus"></i>
            Pridať nový kontakt
        </a>

        <div class="w-full md:w-1/2">
            <input type="text" x-model="nameFilter" placeholder="Filtrovať podľa mena"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow-md">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="border-b text-gray-700">
                    <th class="py-2 px-4 text-left">Meno</th>
                    <th class="py-2 px-4 text-left">E-mail</th>
                    <th class="py-2 px-4 text-left">Oslovenie</th>
                    <th class="py-2 px-4 text-left">Pohlavie</th>
                    <th class="py-2 px-4 text-left">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                    <template x-if="'{{ strtolower($contact->name) }}'.includes(nameFilter.toLowerCase()) || nameFilter === ''">
                        <tr class="border-b hover:bg-gray-100 text-gray-800">
                            <td class="py-2 px-4">{{ $contact->name }}</td>
                            <td class="py-2 px-4">{{ $contact->email }}</td>
                            <td class="py-2 px-4">{{ ucfirst($contact->salutation) }}</td>
                            <td class="py-2 px-4">{{ ucfirst($contact->gender) }}</td>
                            <td class="py-2 px-4 flex items-center gap-3"> 
                                <a href="{{ route('contacts.edit', $contact->id) }}" class="text-blue-500 hover:text-blue-700 transform hover:scale-110 transition duration-200">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transform hover:scale-110 transition duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </template>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function contactFilter() {
        return {
            nameFilter: ''
        }
    }
</script>
@endpush

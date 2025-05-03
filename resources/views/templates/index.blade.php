@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="templateFilter()">
    <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2 text-gray-800">
        <i class="fas fa-file-alt text-blue-500"></i>
        Šablóny
    </h2>

    <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <a href="{{ route('templates.create') }}"
           class="inline-flex items-center gap-2 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
            <i class="fas fa-plus"></i>
            Nová šablóna
        </a>

        <div class="w-full md:w-1/2">
            <input type="text" x-model="nameFilter" placeholder="Filtrovať podľa názvu šablóny"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow-md">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="border-b text-gray-700">
                    <th class="py-2 px-4 text-left">Názov</th>
                    <th class="py-2 px-4 text-left">Predmet</th>
                    <th class="py-2 px-4 text-left">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $t)
                    <template x-if="'{{ strtolower($t->name) }}'.includes(nameFilter.toLowerCase()) || nameFilter === ''">
                        <tr class="border-b hover:bg-gray-100 text-gray-800 cursor-pointer" onclick="window.location='{{ route('templates.show', $t) }}'">
                            <td class="py-2 px-4">{{ $t->name }}</td>
                            <td class="py-2 px-4">{{ $t->subject }}</td>
                            <td class="py-2 px-4 flex items-center gap-3" onclick="event.stopPropagation();">
                                <!-- Edit -->
                                <a href="{{ route('templates.edit', $t) }}"
                                   class="text-blue-500 hover:text-blue-700 transform hover:scale-110 transition duration-200"
                                   title="Upraviť">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- Copy -->
                                <form action="{{ route('templates.copy', $t) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-gray-500 hover:text-gray-700 transform hover:scale-110 transition duration-200"
                                            title="Kopírovať">
                                        <i class="fas fa-clone"></i>
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ route('templates.destroy', $t) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 hover:text-red-700 transform hover:scale-110 transition duration-200"
                                            title="Zmazať">
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
    function templateFilter() {
        return {
            nameFilter: ''
        }
    }
</script>
@endpush

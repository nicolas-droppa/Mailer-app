@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2">
            <i class="fas fa-user-plus"></i>
            Pridať nový kontakt
        </h2>

        <form action="{{ route('contacts.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Meno</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('name') border-red-500 @enderror">
                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E‑mail</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('email') border-red-500 @enderror">
                @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="salutation" class="block text-sm font-medium text-gray-700">Oslovenie</label>
                <select name="salutation" id="salutation" required
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('salutation') border-red-500 @enderror">
                    <option value="tykanie" {{ old('salutation') == 'tykanie' ? 'selected' : '' }}>Tykanie</option>
                    <option value="vykanie" {{ old('salutation') == 'vykanie' ? 'selected' : '' }}>Vykanie</option>
                </select>
                @error('salutation')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Pohlavie</label>
                <select name="gender" id="gender" required
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('gender') border-red-500 @enderror">
                    <option value="muž" {{ old('gender') == 'muž' ? 'selected' : '' }}>Muž</option>
                    <option value="žena" {{ old('gender') == 'žena' ? 'selected' : '' }}>Žena</option>
                </select>
                @error('gender')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition duration-200 transform hover:scale-105">
                <i class="fas fa-save mr-2"></i> Uložiť kontakt
            </button>

            <div class="text-center mt-2">
                <a href="{{ route('contacts.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i> Späť na zoznam
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

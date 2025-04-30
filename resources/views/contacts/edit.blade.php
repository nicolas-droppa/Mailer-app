@extends('layouts.app')

@section('content')
<div class="w-full bg-gray-50 py-12">
    <div class="max-w-full mx-auto px-4">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md h-screen flex flex-col justify-center">
            <h2 class="text-lg font-bold text-center mb-4 text-cyan-600">Upraviť kontakt</h2>

            <form action="{{ route('contacts.update', $contact) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="w-full">
                    <label for="name" class="block text-sm font-medium text-gray-700">Meno</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $contact->name) }}" required
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-cyan-200 focus:border-cyan-400 @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="w-full">
                    <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $contact->email) }}" required
                           class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-cyan-200 focus:border-cyan-400 @error('email') border-red-500 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="w-full">
                    <label for="salutation" class="block text-sm font-medium text-gray-700">Oslovenie</label>
                    <select name="salutation" id="salutation" required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-cyan-200 focus:border-cyan-400 @error('salutation') border-red-500 @enderror">
                        <option value="tykanie" {{ old('salutation', $contact->salutation)=='tykanie'?'selected':'' }}>Tykanie</option>
                        <option value="vykanie" {{ old('salutation', $contact->salutation)=='vykanie'?'selected':'' }}>Vykanie</option>
                    </select>
                    @error('salutation')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="w-full">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Pohlavie</label>
                    <select name="gender" id="gender" required
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-cyan-200 focus:border-cyan-400 @error('gender') border-red-500 @enderror">
                        <option value="muž" {{ old('gender', $contact->gender)=='muž'?'selected':'' }}>Muž</option>
                        <option value="žena" {{ old('gender', $contact->gender)=='žena'?'selected':'' }}>Žena</option>
                    </select>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="w-full">
                    <button type="submit" class="w-full py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md transition transform hover:scale-105 hover:bg-cyan-600">
                        Aktualizovať kontakt
                    </button>
                </div>

                <div class="w-full text-center mt-2">
                    <a href="{{ route('contacts.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Späť na zoznam</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center h-screen w-full mt-0">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-lg font-bold text-center mb-4 text-cyan-600">Pridať nový kontakt</h2>

        <form action="{{ route('contacts.store') }}" method="POST" class="space-y-3">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Meno</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-cyan-200 focus:border-cyan-400 @error('name') border-red-500 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-cyan-200 focus:border-cyan-400 @error('email') border-red-500 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="salutation" class="block text-sm font-medium text-gray-700">Oslovenie</label>
                <select name="salutation" id="salutation" required
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-cyan-200 focus:border-cyan-400 @error('salutation') border-red-500 @enderror">
                    <option value="tykanie" {{ old('salutation')=='tykanie'?'selected':'' }}>Tykanie</option>
                    <option value="vykanie" {{ old('salutation')=='vykanie'?'selected':'' }}>Vykanie</option>
                </select>
                @error('salutation')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Pohlavie</label>
                <select name="gender" id="gender" required
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-cyan-200 focus:border-cyan-400 @error('gender') border-red-500 @enderror">
                    <option value="muž" {{ old('gender')=='muž'?'selected':'' }}>Muž</option>
                    <option value="žena" {{ old('gender')=='žena'?'selected':'' }}>Žena</option>
                </select>
                @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <button type="submit" style="background: #06b6d4; color: white;" class="w-full py-2 px-4 rounded-md shadow hover:opacity-90 transition">
                    Uložiť kontakt
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
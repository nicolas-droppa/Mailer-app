@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2">
            <i class="fas fa-edit"></i>
            Upraviť e‑mail
        </h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('emails.update', $email) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Predmet -->
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Predmet</label>
                <input type="text" name="subject" id="subject" required
                       value="{{ old('subject', $email->subject) }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>

            <!-- Telo e‑mailu -->
            <div>
                <label for="body" class="block text-sm font-medium text-gray-700">Telo e‑mailu</label>
                <textarea name="body" id="body" rows="6" required
                          class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('body', $email->body) }}</textarea>
            </div>

            <!-- Príloha -->
            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-700">Príloha (obrázok, PDF)</label>
                <input id="attachment" name="attachment" type="file" class="mt-1 w-full text-sm text-gray-600
                            file:mr-4 file:py-2 file:px-4
                            file:rounded file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-100 file:text-blue-700
                            hover:file:bg-blue-200">
                @error('attachment')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Aktuálna príloha -->
            @if ($email->attachment_path)
            <div>
                <label class="block text-sm font-medium text-gray-700">Aktuálna príloha</label>
                <p class="mt-1 text-sm">
                    <a href="{{ asset('storage/' . $email->attachment_path) }}"
                    target="_blank"
                    class="text-blue-600 underline hover:text-blue-800">
                        Stiahnuť prílohu
                    </a>
                </p>
            </div>
            @endif


            <!-- Odoslať -->
            <button type="submit"
                    class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition duration-200 transform hover:scale-105">
                <i class="fas fa-save mr-2"></i> Uložiť zmeny
            </button>
        </form>

        <div class="text-center mt-2">
            <a href="{{ route('emails.history') }}" class="text-sm text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Späť na históriu e‑mailov
            </a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="{ copied: false }">
    <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2 text-gray-800">
        <i class="fas fa-eye text-blue-500"></i>
        Detail šablóny
    </h2>

    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('templates.index') }}"
        class="inline-flex items-center gap-2 text-sm text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded transition">
            <i class="fas fa-arrow-left"></i>
            Späť na zoznam
        </a>

        <div class="flex gap-3">
            <!-- Edit -->
            <a href="{{ route('templates.edit', $template) }}" class="text-blue-500 hover:text-blue-700" title="Upraviť">
                <i class="fas fa-pen"></i>
            </a>

            <!-- Copy -->
            <form action="{{ route('templates.copy', $template) }}" method="POST">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-gray-700" title="Kopírovať">
                    <i class="fas fa-clone"></i>
                </button>
            </form>

            <!-- Delete -->
            <form action="{{ route('templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Naozaj chcete vymazať túto šablónu?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700" title="Zmazať">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Názov</h3>
            <p class="text-gray-700">{{ $template->name }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Predmet</h3>
            <p class="text-gray-700">{{ $template->subject }}</p>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Obsah</h3>
            <div class="bg-gray-100 p-4 rounded relative">
                <pre class="whitespace-pre-wrap break-words text-gray-700" id="templateContent">{{ $template->body }}</pre>
                <button
                    @click="navigator.clipboard.writeText(document.getElementById('templateContent').innerText); copied = true; setTimeout(() => copied = false, 2000)"
                    class="absolute top-2 right-2 text-sm text-gray-600 hover:text-blue-600"
                    title="Kopírovať obsah">
                    <i class="fas fa-copy"></i>
                </button>
                <span x-show="copied" x-transition class="absolute bottom-2 right-2 text-xs text-green-600 bg-white px-2 py-1 rounded shadow">
                    Skopírované!
                </span>
            </div>
        </div>

        {{-- Príloha --}}
        <div>
            <label for="attachment" class="block text-sm font-medium text-gray-700">Príloha (obrázok, PDF)</label>
            <input
                id="attachment"
                name="attachment"
                type="file"
                class="mt-1 w-full text-sm text-gray-600
                    file:mr-4 file:py-2 file:px-4
                    file:rounded file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-100 file:text-blue-700
                    hover:file:bg-blue-200"
            >
            @error('attachment')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror

            @if ($template->attachment_path)
                <div class="mt-4">
                    <p class="text-sm text-gray-700 mb-2">Aktuálna príloha:</p>
                    <a href="{{ asset('storage/' . $template->attachment_path) }}"
                    target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded hover:bg-blue-200 transition">
                        <i class="fas fa-paperclip mr-2"></i>
                        Zobraziť / Stiahnuť prílohu
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

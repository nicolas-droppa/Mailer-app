@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
        <h3 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2">
            <i class="fas fa-file-medical"></i>
            Nová šablóna
        </h3>

        <form id="template-form" action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Názov</label>
                <input id="name" name="name" value="{{ old('name') }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('name') border-red-500 @enderror">
                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Predmet</label>
                <input id="subject" name="subject" value="{{ old('subject') }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('subject') border-red-500 @enderror">
                @error('subject')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-gray-700">Telo</label>
                <textarea id="body" name="body" rows="5" class="hidden">{{ old('body') }}</textarea>
                <div id="editor">{!! old('body') !!}</div>
                @error('body')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-700">Príloha (obrázok/PDF)</label>
                <input id="attachment" name="attachment" type="file"
                       class="mt-1 block w-full text-sm text-gray-600
                              file:mr-4 file:py-2 file:px-4
                              file:rounded file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-100 file:text-blue-700
                              hover:file:bg-blue-200" />
                @error('attachment')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition transform hover:scale-105 flex items-center justify-center gap-2">
                <i class="fas fa-save"></i>
                Uložiť šablónu
            </button>

            <div class="text-center mt-2">
                <a href="{{ route('templates.index') }}" class="text-sm text-gray-600 hover:text-gray-800 inline-flex items-center gap-1">
                    <i class="fas fa-arrow-left"></i>
                    Späť na zoznam šablón
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let editor;

    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(newEditor => {
            editor = newEditor;
        })
        .catch(error => {
            console.error(error);
        });

    document.getElementById('template-form').addEventListener('submit', function (e) {
        const editorData = editor.getData().trim();
        document.getElementById('body').value = editorData;

        if (!editorData) {
            alert('Pole „Telo“ je povinné.');
            e.preventDefault();
        }
    });
</script>
@endpush

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2">
            <i class="fas fa-file-alt"></i>
            Upraviť šablónu
        </h2>

        <form id="template-edit-form" action="{{ route('templates.update', $template) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Názov -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Názov</label>
                <input
                    id="name"
                    name="name"
                    value="{{ old('name', $template->name) }}"
                    required
                    class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('name') border-red-500 @enderror"
                >
                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Predmet -->
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Predmet</label>
                <input
                    id="subject"
                    name="subject"
                    value="{{ old('subject', $template->subject) }}"
                    required
                    class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('subject') border-red-500 @enderror"
                >
                @error('subject')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Telo -->
            <div>
                <label for="body" class="block text-sm font-medium text-gray-700">Telo</label>
                <textarea
                    id="body"
                    name="body"
                    class="hidden"
                >{{ old('body', $template->body) }}</textarea>
                <div id="editor">{!! old('body', $template->body) !!}</div>
                @error('body')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Príloha -->
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

            <button
                type="submit"
                class="w-full flex items-center justify-center gap-2 py-2 px-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition transform hover:scale-105 duration-200"
            >
                <i class="fas fa-save"></i>
                Aktualizovať
            </button>

            <div class="text-center mt-2">
                <a
                    href="{{ route('templates.index') }}"
                    class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-800"
                >
                    <i class="fas fa-arrow-left"></i>
                    Späť na zoznam
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

    document.getElementById('template-edit-form').addEventListener('submit', function (e) {
        const editorData = editor.getData().trim();
        document.getElementById('body').value = editorData;

        if (!editorData) {
            alert('Pole „Telo“ je povinné.');
            e.preventDefault();
        }
    });
</script>
@endpush

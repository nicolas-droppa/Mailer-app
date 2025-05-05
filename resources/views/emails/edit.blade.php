@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2">
            <i class="fas fa-envelope-open-text"></i>
            Upraviť e-mail
        </h2>

        <form id="email-form" action="{{ route('emails.update', $email) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Predmet</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject', $email->subject) }}" required
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('subject') border-red-500 @enderror">
                @error('subject') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-gray-700">Telo e-mailu</label>
                <textarea name="body" id="body" rows="6"
                          class="hidden">{{ old('body', $email->body) }}</textarea>
                <div id="editor">{!! old('body', $email->body) !!}</div>
                @error('body') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-700">Príloha (PDF)</label>

                @if ($email->attachment_path)
                    <p class="text-sm mb-1">
                        Aktuálna príloha:
                        <a href="{{ asset('storage/' . $email->attachment_path) }}" target="_blank" class="text-blue-600 underline">
                            Zobraziť
                        </a>
                    </p>
                @endif

                <input type="file" name="attachment" id="attachment"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('attachment') border-red-500 @enderror">
                @error('attachment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition duration-200 transform hover:scale-105">
                <i class="fas fa-save mr-2"></i>
                Uložiť zmeny
            </button>

            <div class="text-center mt-2">
                <a href="{{ route('emails.history') }}" class="text-sm text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i> Späť na zoznam e-mailov
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

    // Sync CKEditor content before submitting
    document.getElementById('email-form').addEventListener('submit', function (e) {
        const editorData = editor.getData().trim();
        document.getElementById('body').value = editorData;

        if (!editorData) {
            alert('Pole „Telo e‑mailu“ je povinné.');
            e.preventDefault();
        }
    });
</script>
@endpush

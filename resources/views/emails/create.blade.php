@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2">
            <i class="fas fa-paper-plane"></i>
            Odoslať e‑mail
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

        <form action="{{ route('emails.send') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <div>
                <label for="template_id" class="block text-sm font-medium text-gray-700">Šablóna (nepovinné)</label>
                <select name="template_id" id="template_id"
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">— Vyber šablónu —</option>
                    @foreach($templates as $t)
                        <option value="{{ $t->id }}"
                                data-subject="{{ $t->subject }}"
                                data-body="{{ htmlentities($t->body) }}"
                                data-attachment="{{ $t->attachment_path ?? '' }}"
                                {{ old('template_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Predmet</label>
                <input type="text" name="subject" id="subject" required
                       value="{{ old('subject') }}"
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-gray-700">Telo e‑mailu</label>
                <textarea name="body" id="body" rows="6"
                          class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('body') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Príjemcovia</label>
                <div class="flex items-center gap-3 mb-2">
                    <button type="button"
                            onclick="openModal()"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Vybrať príjemcov
                    </button>
                    <div id="selected-count" class="text-sm text-gray-600">{{ is_array(old('contact_ids')) ? count(old('contact_ids')) : 0 }} vybraných</div>
                </div>
                <div id="recipients-container">
                    @if (is_array(old('contact_ids')))
                        @foreach (old('contact_ids') as $id)
                            <input type="hidden" name="contact_ids[]" value="{{ $id }}">
                        @endforeach
                    @endif
                </div>
            </div>

            <div>
                <label for="send_option" class="block text-sm font-medium text-gray-700">Kedy odoslať?</label>
                <select name="send_option" id="send_option" required
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="now" {{ old('send_option') === 'now' ? 'selected' : '' }}>Odoslať hneď</option>
                    <option value="later" {{ old('send_option') === 'later' ? 'selected' : '' }}>Odoslať neskôr</option>
                </select>
            </div>

            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-700">Príloha (obrázok, PDF)</label>
                <input id="attachment" name="attachment" type="file" class="mt-1 w-full text-sm text-gray-600
                            file:mr-4 file:py-2 file:px-4
                            file:rounded file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-100 file:text-blue-700
                            hover:file:bg-blue-200">
                <input type="hidden" name="attachment_path" id="attachment_path" value="{{ old('attachment_path') }}">
                @error('attachment')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div id="template-attachment-preview" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Príloha zo šablóny</label>
                <p class="mt-1 text-sm">
                    <a id="template-attachment-link"
                    href="#"
                    target="_blank"
                    class="text-blue-600 underline hover:text-blue-800">
                        Stiahnuť prílohu
                    </a>
                </p>
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition duration-200 transform hover:scale-105">
                <i class="fas fa-paper-plane mr-2"></i> Odoslať
            </button>
        </form>

        <div class="text-center mt-2">
            <a href="{{ route('emails.history') }}" class="text-sm text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Späť na históriu e‑mailov
            </a>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 z-[9999] hidden bg-black/70 flex items-center justify-center h-screen w-screen">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Vyberte príjemcov</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <input type="text" placeholder="Filtrovať..." id="filterInput"
               class="mb-4 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300">

        <div id="contactsList" class="max-h-64 overflow-y-auto space-y-2">
            @foreach($contacts->sortBy('name') as $c)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" class="contact-checkbox" value="{{ $c->id }}"
                           data-name="{{ strtolower($c->name) }}"
                           onchange="updateSelected(this)"
                           {{ is_array(old('contact_ids')) && in_array($c->id, old('contact_ids')) ? 'checked' : '' }}>
                    <span>{{ $c->name }} ({{ $c->email }})</span>
                </label>
            @endforeach
        </div>

        <div class="mt-4 text-right">
            <button onclick="closeModal()"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                Hotovo
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('modal');
    const filterInput = document.getElementById('filterInput');
    const recipientsContainer = document.getElementById('recipients-container');
    const selectedCount = document.getElementById('selected-count');

    function openModal() {
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    function updateSelected(checkbox) {
        const value = checkbox.value;
        let existing = recipientsContainer.querySelector('input[value="' + value + '"]');
        if (checkbox.checked && !existing) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'contact_ids[]';
            hidden.value = value;
            recipientsContainer.appendChild(hidden);
        } else if (!checkbox.checked && existing) {
            recipientsContainer.removeChild(existing);
        }
        selectedCount.textContent = recipientsContainer.children.length + ' vybraných';
    }

    function decodeHTMLEntities(text) {
        const textarea = document.createElement('textarea');
        textarea.innerHTML = text;
        return textarea.value;
    }

    let editor;
    ClassicEditor
        .create(document.querySelector('#body'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'undo', 'redo']
        })
        .then(newEditor => {
            editor = newEditor;
        })
        .catch(error => {
            console.error(error);
        });

    const templateSelect = document.getElementById('template_id');
    const subjectField = document.getElementById('subject');
    const attachHidden = document.getElementById('attachment_path');

    templateSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const subject = selectedOption.getAttribute('data-subject') || '';
        const body = selectedOption.getAttribute('data-body') || '';
        const attachment = selectedOption.getAttribute('data-attachment') || '';

        subjectField.value = subject;
        if (editor && body) {
            editor.setData(decodeHTMLEntities(body));
        }

        const attachmentPreview = document.getElementById('template-attachment-preview');
        const attachmentLink = document.getElementById('template-attachment-link');

        if (attachment) {
            attachHidden.value = attachment;
            attachmentLink.href = `/storage/${attachment}`;
            attachmentPreview.classList.remove('hidden');
        } else {
            attachHidden.value = '';
            attachmentLink.href = '#';
            attachmentPreview.classList.add('hidden');
        }
    });

    filterInput.addEventListener('input', function () {
        const query = filterInput.value.toLowerCase();
        const contacts = document.querySelectorAll('.contact-checkbox');
        contacts.forEach(function (checkbox) {
            const name = checkbox.getAttribute('data-name');
            checkbox.closest('label').style.display = name.includes(query) ? '' : 'none';
        });
    });

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

@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-3xl font-semibold text-cyan-700 mb-6 flex items-center gap-2">
        <i class="fas fa-paper-plane"></i>
        Odoslať e‑mail
    </h2>

    <form action="{{ route('emails.send') }}" method="POST" class="bg-white p-6 rounded shadow-md space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Šablóna -->
            <div>
                <label for="template_id" class="block text-sm font-medium text-gray-700 mb-1">Šablóna</label>
                <select name="template_id" id="template_id" required
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    @foreach($templates as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Čas odoslania -->
            <div>
                <label for="send_option" class="block text-sm font-medium text-gray-700 mb-1">Kedy odoslať?</label>
                <select name="send_option" id="send_option" required
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="now" selected>Odoslať hneď</option>
                    <option value="later">Odoslať neskôr</option>
                </select>
            </div>

            <!-- Príjemcovia -->
            <div class="col-span-1 md:col-span-2 lg:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Príjemcovia</label>
                <div class="flex items-center gap-3 mb-2">
                    <button type="button"
                            onclick="openModal()"
                            class="px-4 py-2 bg-cyan-500 text-white rounded hover:bg-cyan-600 transition">
                        Vybrať príjemcov
                    </button>
                    <div id="selected-count" class="text-sm text-gray-600">0 vybraných</div>
                </div>
                <div id="recipients-container"></div>
            </div>
        </div>

        <!-- Tlačidlo Odoslať -->
        <div class="text-right pt-4">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-cyan-600 text-white py-2 px-5 rounded hover:bg-cyan-700 transition transform hover:scale-105">
                <i class="fas fa-paper-plane mr-2"></i> Odoslať
            </button>
        </div>
    </form>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 z-[9999] hidden bg-black/70 flex items-center justify-center h-screen w-screen">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Vyberte príjemcov</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <input type="text" placeholder="Filtrovať..." id="filterInput"
               class="mb-4 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">

        <div id="contactsList" class="max-h-64 overflow-y-auto space-y-2">
            @foreach($contacts->sortBy('name') as $c)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" class="contact-checkbox" value="{{ $c->id }}"
                           data-name="{{ strtolower($c->name) }}"
                           onchange="updateSelected(this)">
                    <span>{{ $c->name }} ({{ $c->email }})</span>
                </label>
            @endforeach
        </div>

        <div class="mt-4 text-right">
            <button onclick="closeModal()"
                    class="px-4 py-2 bg-cyan-600 text-white rounded hover:bg-cyan-700 transition">
                Hotovo
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
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
        let existing = recipientsContainer.querySelector('input[value=\"' + value + '\"]');
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

    filterInput.addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('.contact-checkbox').forEach(cb => {
            const name = cb.dataset.name;
            cb.closest('label').style.display = name.includes(filter) ? 'flex' : 'none';
        });
    });
</script>
@endpush

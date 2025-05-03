@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="emailHistory()">
    <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2 text-gray-800">
        <i class="fas fa-envelope text-blue-500"></i>
        História e-mailov
    </h2>

    <div class="mb-4">
        <a href="{{ route('emails.create') }}"
           class="inline-flex items-center gap-2 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
            <i class="fas fa-plus"></i>
            Nový e-mail
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter + Status Switch -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
        <!-- Subject Search -->
        <div class="w-full md:w-1/2">
            <input type="text" x-model="subjectFilter" placeholder="Filtrovať podľa predmetu"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <!-- Status Switch -->
        <div class="flex items-center gap-2">
            <button @click="statusFilter = 'all'"
                    :class="statusFilter === 'all' ? activeBtn : inactiveBtn">
                Všetky
            </button>
            <button @click="statusFilter = 'sent'"
                    :class="statusFilter === 'sent' ? activeBtn : inactiveBtn">
                Odoslané
            </button>
            <button @click="statusFilter = 'pending'"
                    :class="statusFilter === 'pending' ? activeBtn : inactiveBtn">
                Čakajúce
            </button>
        </div>
    </div>

    <form id="bulkForm" action="{{ route('emails.bulkSend') }}" method="POST">
        @csrf

        <div class="bg-white p-6 rounded shadow-md overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="border-b text-gray-700 text-sm uppercase">
                        <th class="py-2 px-4"><input type="checkbox" id="checkAll" class="accent-blue-600" @change="toggleAll"></th>
                        <th class="py-2 px-4 text-left">Predmet</th>
                        <th class="py-2 px-4 text-left">Príjemcovia</th>
                        <th class="py-2 px-4 text-left">Dátum</th>
                        <th class="py-2 px-4 text-left">Stav</th>
                        <th class="py-2 px-4 text-left">Akcie</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $m)
                        <template x-if="
                            (subjectFilter === '' || '{{ strtolower($m->subject) }}'.includes(subjectFilter.toLowerCase())) &&
                            (
                                statusFilter === 'all' ||
                                (statusFilter === 'sent' && '{{ $m->status }}' === 'odoslane') ||
                                (statusFilter === 'pending' && '{{ $m->status }}' === 'caka')
                            )
                        ">
                            <tr class="border-b hover:bg-gray-100 text-gray-800 cursor-pointer" @click="window.location='{{ route('emails.show', $m) }}'">
                                <td class="py-2 px-4" @click.stop>
                                    @if($m->status === 'caka')
                                        <input form="bulkForm" type="checkbox" name="email_ids[]" value="{{ $m->id }}" class="email-checkbox accent-blue-600">
                                    @endif
                                </td>
                                <td class="py-2 px-4 font-medium text-gray-800">{{ $m->subject }}</td>
                                <td class="py-2 px-4 text-sm">
                                    @if(count($m->recipients) === 1)
                                        <span class="text-gray-700">{{ $m->recipients[0] }}</span>
                                    @else
                                        <span class="text-gray-500">{{ count($m->recipients) }} príjemcov</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 text-gray-500">{{ $m->created_at->timezone('Europe/Bratislava')->format('d.m.Y H:i') }}</td>
                                <td class="py-2 px-4">
                                    @if($m->status === 'caka')
                                        <span class="inline-block bg-yellow-400 text-white text-xs font-semibold px-3 py-1 rounded-full">Čaká</span>
                                    @else
                                        <span class="inline-block bg-blue-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Odoslané</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 flex items-center gap-3" @click.stop>
                                    @if($m->status === 'caka')
                                        <form action="{{ route('emails.sendOne', $m) }}" method="POST" class="inline" @click.stop>
                                            @csrf
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-800" title="Odoslať">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('emails.edit', $m) }}" class="text-blue-500 hover:text-blue-700" title="Upraviť" @click.stop>
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <button 
                                            @click.prevent.stop="openConfirm('delete', {{ $m->id }})" 
                                            class="text-red-500 hover:text-red-700" 
                                            title="Zmazať">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                    <button 
                                        @click.prevent.stop="openConfirm('copy', {{ $m->id }})" 
                                        class="text-gray-500 hover:text-gray-700" 
                                        title="Kopírovať">
                                        <i class="fas fa-clone"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-end">
            <button form="bulkForm" type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                <i class="fas fa-paper-plane"></i>
                Odoslať vybrané
            </button>
        </div>
    </form>

    <!-- Confirmation Modal -->
    <div x-show="confirmOpen" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Potvrdenie akcie</h3>
            <p class="mb-6" x-text="confirmAction == 'delete' ? 'Naozaj chcete vymazať tento e-mail?' : 'Naozaj chcete kopírovať tento e-mail?'"></p>
            <div class="flex justify-end gap-3">
                <button @click="closeConfirm()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Zrušiť</button>
                <form :action="actionUrl" method="POST" @click.stop>
                    @csrf
                    <template x-if="confirmAction == 'delete'">
                        <input type="hidden" name="_method" value="DELETE">
                    </template>
                    <button
                    type="submit"
                    :class="confirmAction === 'delete'
                        ? 'px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600'
                        : 'px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600'"
                    x-text="confirmAction === 'delete' ? 'Vymazať' : 'Kopírovať'">
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function emailHistory() {
        return {
            confirmOpen: false,
            confirmAction: null,
            confirmId: null,
            actionUrl: '',
            subjectFilter: '',
            statusFilter: 'all',
            activeBtn: 'px-4 py-1.5 bg-blue-600 text-white rounded',
            inactiveBtn: 'px-4 py-1.5 bg-gray-200 text-gray-700 rounded hover:bg-gray-300',
            openConfirm(action, id) {
                this.confirmAction = action;
                this.confirmId = id;
                if (action === 'delete') {
                    this.actionUrl = `/emails/${id}`;
                } else if (action === 'copy') {
                    this.actionUrl = `/emails/${id}/copy`;
                }
                this.confirmOpen = true;
            },
            closeConfirm() {
                this.confirmOpen = false;
                this.confirmAction = null;
                this.confirmId = null;
                this.actionUrl = '';
            },
            toggleAll(e) {
                document.querySelectorAll('.email-checkbox').forEach(cb => cb.checked = e.target.checked);
            }
        }
    }
</script>
@endpush

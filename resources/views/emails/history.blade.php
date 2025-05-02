@extends('layouts.app')

@section('content')

<div class="container mx-auto py-10 px-4">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-4xl font-bold text-cyan-700 flex items-center gap-2">
            <svg class="w-8 h-8 text-cyan-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z" />
            </svg>
            História e-mailov
        </h2>
        <a href="{{ route('emails.create') }}" class="bg-gradient-to-r from-fuchsia-500 to-pink-500 text-white px-5 py-3 rounded-xl shadow-lg hover:shadow-pink-400/60 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Nový e-mail
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-100 text-emerald-900 rounded-lg shadow">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('emails.bulkSend') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100 text-gray-800 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3"><input type="checkbox" id="checkAll" class="accent-cyan-600"></th>
                        <th class="px-4 py-3">Predmet</th>
                        <th class="px-4 py-3">Príjemcovia</th>
                        <th class="px-4 py-3">Dátum</th>
                        <th class="px-4 py-3">Stav</th>
                        <th class="px-4 py-3">Akcie</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($history as $m)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                @if($m->status === 'caka')
                                    <input type="checkbox" name="email_ids[]" value="{{ $m->id }}" class="email-checkbox accent-cyan-600">
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $m->subject }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                <ul class="list-disc ml-4 space-y-1">
                                    @foreach($m->recipients as $email)
                                        <li>{{ $email }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $m->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-4 py-3">
                                @if($m->status === 'caka')
                                    <span class="bg-yellow-400 text-white px-3 py-1 rounded-full text-xs font-bold">Čaká</span>
                                @else
                                    <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-bold">Odoslané</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($m->status === 'caka')
                                    <form action="{{ route('emails.sendOne', $m) }}" method="POST" onsubmit="return confirm('Naozaj chcete odoslať tento e-mail?')">
                                        @csrf
                                        <button type="submit" class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium shadow-sm flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-2-12 14z" />
                                            </svg>
                                            Odoslať
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm">–</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-3 rounded-xl hover:shadow-lg hover:from-blue-700 hover:to-cyan-600 transition-all duration-300 flex items-center gap-2 text-base font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                Odoslať vybrané
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('checkAll')?.addEventListener('change', function () {
        document.querySelectorAll('.email-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
</script>
@endpush

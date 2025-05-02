@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
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

    <form action="{{ route('emails.bulkSend') }}" method="POST">
        @csrf

        <div class="bg-white p-6 rounded shadow-md">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="border-b text-gray-700 text-sm uppercase">
                        <th class="py-2 px-4"><input type="checkbox" id="checkAll" class="accent-blue-600"></th>
                        <th class="py-2 px-4 text-left">Predmet</th>
                        <th class="py-2 px-4 text-left">Príjemcovia</th>
                        <th class="py-2 px-4 text-left">Dátum</th>
                        <th class="py-2 px-4 text-left">Stav</th>
                        <th class="py-2 px-4 text-left">Akcie</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $m)
                        <tr class="border-b hover:bg-gray-100 text-gray-800">
                            <td class="py-2 px-4">
                                @if($m->status === 'caka')
                                    <input type="checkbox" name="email_ids[]" value="{{ $m->id }}" class="email-checkbox accent-blue-600">
                                @endif
                            </td>
                            <td class="py-2 px-4 font-medium">{{ $m->subject }}</td>
                            <td class="py-2 px-4 text-sm">
                                <ul class="list-disc ml-4 space-y-1">
                                    @foreach($m->recipients as $email)
                                        <li>{{ $email }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-2 px-4 text-gray-500">{{ $m->created_at->format('d.m.Y H:i') }}</td>
                            <td class="py-2 px-4">
                                @if($m->status === 'caka')
                                    <span class="inline-block bg-yellow-400 text-white text-xs font-semibold px-3 py-1 rounded-full">Čaká</span>
                                @else
                                    <span class="inline-block bg-blue-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Odoslané</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 flex items-center gap-3">
                                @if($m->status === 'caka')
                                    <a href="{{ route('emails.edit', $m) }}" class="text-blue-500 hover:text-blue-700 transform hover:scale-110 transition duration-200" title="Upraviť">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('emails.sendOne', $m) }}" method="POST" onsubmit="return confirm('Naozaj chcete odoslať tento e-mail?')" class="inline">
                                        @csrf
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-800 transform hover:scale-110 transition duration-200" title="Odoslať">
                                            <i class="fas fa-paper-plane"></i>
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

        <div class="mt-4 flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                <i class="fas fa-paper-plane"></i>
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

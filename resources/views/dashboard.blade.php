@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6" x-data>
    <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2 text-gray-800">
        <i class="fas fa-tachometer-alt text-blue-500"></i>
        Prehľad
    </h2>

    {{-- Statistiky --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-address-book text-blue-500"></i>
                Počet kontaktov
            </div>
            <div class="text-3xl font-bold text-blue-600 mt-2">{{ $contactCount }}</div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-file-alt text-purple-500"></i>
                Počet šablón
            </div>
            <div class="text-3xl font-bold text-purple-600 mt-2">{{ $templateCount }}</div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-clock text-yellow-500"></i>
                Čakajúce e-maily
            </div>
            <div class="text-3xl font-bold text-yellow-500 mt-2">{{ $pendingCount }}</div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-paper-plane text-green-500"></i>
                Odoslané e-maily
            </div>
            <div class="text-3xl font-bold text-green-600 mt-2">{{ $sentCount }}</div>
        </div>
    </div>

    {{-- Posledná odoslaná pošta --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-envelope-open-text text-blue-500"></i>
            Posledná odoslaná pošta
        </h3>

        @if ($lastSentEmail)
            <div class="space-y-2 text-gray-700">
                <p><strong>Predmet:</strong> {{ $lastSentEmail->subject }}</p>
                <p><strong>Odoslané:</strong>
                    {{ $lastSentEmail->created_at->timezone('Europe/Bratislava')->format('d.m.Y H:i') }}
                </p>
                <p>
                    <strong>Príjemcovia:</strong>
                    @if(count($lastSentEmail->recipients) === 1)
                        {{ $lastSentEmail->recipients[0] }}
                    @else
                        {{ count($lastSentEmail->recipients) }} príjemcov
                    @endif
                </p>
                <a href="{{ route('emails.show', $lastSentEmail) }}" class="text-blue-600 hover:underline text-sm">
                    Zobraziť detail
                </a>
            </div>
        @else
            <p class="text-gray-500 italic">Zatiaľ nebola odoslaná žiadna pošta.</p>
        @endif
    </div>
</div>
@endsection

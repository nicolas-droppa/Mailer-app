@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6" x-data>
    <h2 class="text-2xl font-semibold mb-4 flex items-center gap-2 text-gray-800">
        <i class="fas fa-tachometer-alt text-blue-500"></i>
        Prehľad
    </h2>

    {{-- Celosystémové štatistiky --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-address-book text-blue-500"></i>
                Počet kontaktov (celkovo)
            </div>
            <div class="text-3xl font-bold text-blue-600 mt-2">{{ $totalContactCount }}</div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-file-alt text-purple-500"></i>
                Počet šablón (celkovo)
            </div>
            <div class="text-3xl font-bold text-purple-600 mt-2">{{ $totalTemplateCount }}</div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-clock text-yellow-500"></i>
                Čakajúce e-maily (celkovo)
            </div>
            <div class="text-3xl font-bold text-yellow-500 mt-2">{{ $totalPendingCount }}</div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-paper-plane text-green-500"></i>
                Odoslané e-maily (celkovo)
            </div>
            <div class="text-3xl font-bold text-green-600 mt-2">{{ $totalSentCount }}</div>
        </div>
    </div>

    {{-- Personalizované štatistiky pre prihláseného používateľa --}}
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Personalizované štatistiky</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="fas fa-address-book text-blue-500"></i>
                    Počet kontaktov (tvojich)
                </div>
                <div class="text-3xl font-bold text-blue-600 mt-2">{{ $contactCount }}</div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="fas fa-file-alt text-purple-500"></i>
                    Počet šablón (tvojich)
                </div>
                <div class="text-3xl font-bold text-purple-600 mt-2">{{ $templateCount }}</div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="fas fa-clock text-yellow-500"></i>
                    Čakajúce e-maily (tvoje)
                </div>
                <div class="text-3xl font-bold text-yellow-500 mt-2">{{ $pendingCount }}</div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="fas fa-paper-plane text-green-500"></i>
                    Odoslané e-maily (tvoje)
                </div>
                <div class="text-3xl font-bold text-green-600 mt-2">{{ $sentCount }}</div>
            </div>
        </div>

        {{-- Posledná odoslaná správa --}}
        <div class="mt-6">
            <h4 class="text-lg font-semibold text-gray-800">Posledná odoslaná správa</h4>
            @if ($lastSentEmail)
                <div class="space-y-2 text-gray-700">
                    <p><strong>Predmet:</strong> {{ $lastSentEmail->subject }}</p>
                    <p><strong>Odoslané:</strong> {{ $lastSentEmail->created_at->timezone('Europe/Bratislava')->format('d.m.Y H:i') }}</p>
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
</div>
@endsection

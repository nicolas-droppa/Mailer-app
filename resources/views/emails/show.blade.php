@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2">
            <i class="fas fa-envelope-open-text"></i>
            Detaily e‑mailu
        </h2>

        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-medium text-gray-700">Predmet</h3>
                <p class="mt-1 text-gray-800">{{ $email->subject }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-700">Telo e‑mailu</h3>
                <div class="mt-1 p-4 bg-gray-50 rounded">
                    {!! nl2br(e($email->body)) !!}
                </div>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-700">Príjemcovia</h3>
                <ul class="list-disc ml-5 mt-1 text-gray-800">
                    @foreach($email->recipients as $rcpt)
                        <li>{{ $rcpt }}</li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-700">Dátum vytvorenia</h3>
                <p class="mt-1 text-gray-600">{{ $email->created_at->format('d.m.Y H:i') }}</p>
            </div>

            <!-- Check if the email has an attachment and display it -->
            @if($email->attachment_path)
                <div>
                    <h3 class="text-sm font-medium text-gray-700">Príloha</h3>
                    <p class="mt-1 text-gray-800">
                        <a href="{{ asset('storage/' . $email->attachment_path) }}" class="text-blue-600 hover:text-blue-800" target="_blank">
                            Stiahnuť prílohu
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <div class="mt-6">
            <a href="{{ route('emails.history') }}" class="text-sm text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i> Späť na históriu e‑mailov
            </a>
        </div>
    </div>
</div>
@endsection

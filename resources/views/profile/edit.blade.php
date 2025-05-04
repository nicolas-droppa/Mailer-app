@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center gap-2">
        <i class="fas fa-user text-blue-500"></i>
        Profil používateľa
    </h2>

    <div class="space-y-6">
        {{-- Osobné údaje --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Zmena hesla --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Zmazanie účtu --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection

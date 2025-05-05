@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2">
            <i class="fas fa-sign-in-alt"></i>
            Prihlásenie
        </h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Heslo</label>
                <input id="password" type="password" name="password" required
                       class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('password') border-red-500 @enderror">
                @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ms-2 text-sm text-gray-600">Zapamätať si ma</span>
                </label>
            </div>

            <!-- Login Button -->
            <button type="submit"
                    class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition duration-200 transform hover:scale-105">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Prihlásiť sa
            </button>

            <!-- Register Link -->
            <div class="text-center mt-4">
                <a href="{{ route('register') }}"
                   class="inline-block text-sm text-blue-600 hover:text-blue-800">
                    Nemáte účet? Zaregistrujte sa
                </a>
            </div>

            <!-- Password Reset Link -->
            <div class="text-center mt-2">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-800"
                       href="{{ route('password.request') }}">
                        Zabudli ste heslo?
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

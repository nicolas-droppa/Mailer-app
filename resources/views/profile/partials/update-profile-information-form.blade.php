<section class="bg-white p-6 rounded">
    <header class="mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-1 flex items-center gap-2">
            <i class="fas fa-id-card text-indigo-500"></i>
            Osobné údaje
        </h3>
        <p class="text-sm text-gray-600">
            Aktualizujte svoje meno a e-mailovú adresu.
        </p>
    </header>

    {{-- Formulár na odoslanie overovacieho emailu --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Formulár na úpravu profilu --}}
    <form method="post" action="{{ route('profile.update') }}" class="space-y-4 mt-4">
        @csrf
        @method('patch')

        {{-- Meno --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Meno</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="mt-1 block w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <x-input-error class="mt-1 text-red-500 text-sm" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="mt-1 block w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <x-input-error class="mt-1 text-red-500 text-sm" :messages="$errors->get('email')" />

            {{-- Overenie e-mailu --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-gray-700">
                    Vaša e-mailová adresa nie je overená.
                    <button form="send-verification"
                            class="ml-2 underline text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded">
                        Kliknite sem pre opätovné odoslanie overovacieho e-mailu.
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-green-600 font-medium text-sm">
                            Nový overovací odkaz bol odoslaný na vašu e-mailovú adresu.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Tlačidlo uložiť --}}
        <div class="flex items-center gap-4">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition">
                <i class="fas fa-save"></i> Uložiť
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600">
                    Uložené.
                </p>
            @endif
        </div>
    </form>
</section>

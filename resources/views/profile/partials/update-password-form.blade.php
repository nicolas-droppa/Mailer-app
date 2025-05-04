<section class="bg-white p-6 rounded">
    <header class="mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-1 flex items-center gap-2">
            <i class="fas fa-key text-yellow-500"></i>
            Zmena hesla
        </h3>
        <p class="text-sm text-gray-600">
            Uistite sa, že používate dlhé a náhodné heslo pre vyššiu bezpečnosť.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4 mt-4">
        @csrf
        @method('put')

        {{-- Aktuálne heslo --}}
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">Aktuálne heslo</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                   class="mt-1 block w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-red-500 text-sm" />
        </div>

        {{-- Nové heslo --}}
        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700">Nové heslo</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                   class="mt-1 block w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-red-500 text-sm" />
        </div>

        {{-- Potvrdenie hesla --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">Potvrdiť heslo</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="mt-1 block w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-red-500 text-sm" />
        </div>

        {{-- Tlačidlo uložiť --}}
        <div class="flex items-center gap-4">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition">
                <i class="fas fa-save"></i> Uložiť
            </button>

            @if (session('status') === 'password-updated')
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

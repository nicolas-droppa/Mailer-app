<section class="bg-white p-6 rounded space-y-4">
    <header>
        <h3 class="text-lg font-semibold text-gray-800 mb-1 flex items-center gap-2">
            <i class="fas fa-user-times text-red-500"></i>
            Zmazanie účtu
        </h3>

        <p class="text-sm text-gray-600">
            Po zmazaní účtu budú všetky údaje nenávratne vymazané. Pred pokračovaním si, prosím, stiahnite všetky dáta, ktoré si želáte ponechať.
        </p>
    </header>

    {{-- Spúšťač modálneho okna --}}
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded transition"
    >
        <i class="fas fa-trash"></i> Zmazať účet
    </button>

    {{-- Potvrdzovacie modálne okno --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-900 mb-2">
                Naozaj chcete zmazať svoj účet?
            </h2>

            <p class="text-sm text-gray-600 mb-4">
                Po zmazaní účtu budú všetky jeho dáta nenávratne odstránené. Na potvrdenie zmazania zadajte svoje heslo.
            </p>

            <div class="mb-4">
                <label for="password" class="sr-only">Heslo</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Heslo"
                    class="block w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1 text-sm text-red-500" />
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded transition">
                    Zrušiť
                </button>

                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded transition">
                    Zmazať účet
                </button>
            </div>
        </form>
    </x-modal>
</section>

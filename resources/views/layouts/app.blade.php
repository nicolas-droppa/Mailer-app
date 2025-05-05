<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CKEditor 5 -->
  <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    <div class="container mx-auto px-4 py-4">
      @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded">
          <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-800 rounded">
          <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
      @endif
    </div>

    @isset($header)
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endisset

    <main>
      @yield('content')
    </main>
  </div>

  <!-- Modal -->
  <div id="variablesModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white w-full max-w-3xl mx-4 p-6 rounded-2xl shadow-2xl relative animate-fade-in">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Návod k písaniu šablón a emailov</h2>
        <button onclick="toggleVariablesModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
      </div>

      <div class="overflow-x-auto mb-6">
        <table class="w-full text-sm text-left border border-gray-200 rounded overflow-hidden">
          <thead class="bg-gray-50 text-gray-700">
            <tr>
              <th class="border px-4 py-2">Premenná</th>
              <th class="border px-4 py-2">Popis</th>
            </tr>
          </thead>
          <tbody class="text-gray-800">
            <tr><td class="border px-4 py-2 font-mono text-blue-600">{{ '{meno} / {priezvisko} / {cele_meno}' }}</td><td class="border px-4 py-2">Meno kontaktu</td></tr>
            <tr><td class="border px-4 py-2 font-mono text-blue-600">{{ '{email}' }}</td><td class="border px-4 py-2">Email kontaktu</td></tr>
            <tr><td class="border px-4 py-2 font-mono text-blue-600">{{ '{pozdravenie}' }}</td><td class="border px-4 py-2">Oslovenie podľa pohlavia a štýlu</td></tr>
            <tr><td class="border px-4 py-2 font-mono text-blue-600">{{ '{ste/si}' }}</td><td class="border px-4 py-2">Sloveso „byť“ v správnom tvare</td></tr>
            <tr><td class="border px-4 py-2 font-mono text-blue-600">{{ '{vas/tvoj}' }}</td><td class="border px-4 py-2">Privlastňovacie zámeno</td></tr>
            <tr><td class="border px-4 py-2 font-mono text-blue-600">{{ '{vam/ti}' }}</td><td class="border px-4 py-2">Zámeno v datíve</td></tr>
            <tr><td class="border px-4 py-2 font-mono text-blue-600">{{ '{vas/ta}' }}</td><td class="border px-4 py-2">Zámeno v akuzatíve</td></tr>
            <tr><td class="border px-4 py-2 font-mono text-blue-600">{{ '{vy/ty}' }}</td><td class="border px-4 py-2">Osobné zámeno</td></tr>
          </tbody>
        </table>
      </div>

      <div class="mt-6">
        <h3 class="text-md font-semibold mb-2 text-gray-700">Ukážky:</h3>

        <div class="flex flex-wrap gap-2 mb-4">
          <button onclick="showExample('template')" class="example-btn bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition"><i class="fa-solid fa-file"></i> - Predloha</button>
          <button onclick="showExample('male_formal')" class="example-btn bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-blue-500 transition"><i class="fa-solid fa-person"></i> - Vykanie</button>
          <button onclick="showExample('male_informal')" class="example-btn bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-blue-500 transition"><i class="fa-solid fa-person"></i> - Tykanie</button>
          <button onclick="showExample('female_formal')" class="example-btn bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-blue-500 transition"><i class="fa-solid fa-person-dress"></i> - Vykanie</button>
          <button onclick="showExample('female_informal')" class="example-btn bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-blue-500 transition"><i class="fa-solid fa-person-dress"></i> - Tykanie</button>
        </div>

        <!-- Ukážky -->
        <div id="example-template" class="example hidden bg-gray-50 p-4 rounded-lg text-sm text-gray-700 space-y-3">
          <p class="font-medium text-gray-600">📄 Predloha</p>
          <p><span class="text-blue-600">{{ '{pozdravenie}' }}</span>,</p><p>rád by som <span class="text-blue-600">{{ '{vas/ta}' }}</span> pozval na oslavu svojich 20. narodenín. Oslava bude 21.05.2025 (v sobotu) o 16:30 na Račianskej 47. Na oslave bude množstvo skvelej zábavy, jedla a drinkov. Verím, že sa <span class="text-blue-600">{{ '{vam/ti}' }}</span> bude páčiť a že sa tam uvidíme!</p>
          <p>Prosím o potvrdenie účasti čo najskôr, ďakujem.</p>
          <p>S pozdravom Janko Hraško</p>
        </div>

        <div id="example-male_formal" class="example hidden bg-gray-50 p-4 rounded-lg text-sm text-gray-700 space-y-3">
          <p class="font-medium text-gray-600">🧑‍💼 Muž – Vykanie</p>
          <p><span class="text-blue-600">Dobrý deň pán Novák,</span></p><p>rád by som <span class="text-blue-600">Vás</span> pozval na oslavu svojich 20. narodenín. Oslava bude 21.05.2025 (v sobotu) o 16:30 na Račianskej 47. Na oslave bude množstvo skvelej zábavy, jedla a drinkov. Verím, že sa <span class="text-blue-600">Vám</span> bude páčiť a že sa tam uvidíme!</p>
          <p>Prosím o potvrdenie účasti čo najskôr, ďakujem.</p>
          <p>S pozdravom Janko Hraško</p>
        </div>

        <div id="example-male_informal" class="example hidden bg-gray-50 p-4 rounded-lg text-sm text-gray-700 space-y-3">
          <p class="font-medium text-gray-600">👨 Muž – Tykanie</p>
          <p><span class="text-blue-600">Ahoj Jano,</span></p>
          <p>rád by som <span class="text-blue-600">Ťa</span> pozval na oslavu svojich 20. narodenín. Oslava bude 21.05.2025 (v sobotu) o 16:30 na Račianskej 47. Na oslave bude množstvo skvelej zábavy, jedla a drinkov. Verím, že sa <span class="text-blue-600">Ti</span> bude páčiť a že sa tam uvidíme!</p>
          <p>Prosím o potvrdenie účasti čo najskôr, ďakujem.</p>
          <p>S pozdravom Janko Hraško</p>
        </div>

        <div id="example-female_formal" class="example hidden bg-gray-50 p-4 rounded-lg text-sm text-gray-700 space-y-3">
          <p class="font-medium text-gray-600">🧑‍💼 Žena – Vykanie</p>
          <p><span class="text-blue-600">Dobrý deň pani Nováková,</span></p>
          <p>rád by som <span class="text-blue-600">Vás</span> pozval na oslavu svojich 20. narodenín. Oslava bude 21.05.2025 (v sobotu) o 16:30 na Račianskej 47. Na oslave bude množstvo skvelej zábavy, jedla a drinkov. Verím, že sa <span class="text-blue-600">Vám</span> bude páčiť a že sa tam uvidíme!</p>
          <p>Prosím o potvrdenie účasti čo najskôr, ďakujem.</p>
          <p>S pozdravom Janko Hraško</p>
        </div>

        <div id="example-female_informal" class="example hidden bg-gray-50 p-4 rounded-lg text-sm text-gray-700 space-y-3">
          <p class="font-medium text-gray-600">👩 Žena – Tykanie</p>
          <p><span class="text-blue-600">Ahoj Janka,</span></p>
          <p>rád by som <span class="text-blue-600">Ťa</span> pozval na oslavu svojich 20. narodenín. Oslava bude 21.05.2025 (v sobotu) o 16:30 na Račianskej 47. Na oslave bude množstvo skvelej zábavy, jedla a drinkov. Verím, že sa <span class="text-blue-600">Ti</span> bude páčiť a že sa tam uvidíme!</p>
          <p>Prosím o potvrdenie účasti čo najskôr, ďakujem.</p>
          <p>S pozdravom Janko Hraško</p>
        </div>
      </div>

      <div class="mt-6 text-right">
        <button onclick="toggleVariablesModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">Zavrieť</button>
      </div>
    </div>
  </div>

  @push('scripts')
<script>
  function toggleVariablesModal() {
    const modal = document.getElementById('variablesModal');
    modal.classList.toggle('hidden');
    modal.classList.toggle('flex');
    showExample('template'); // predvolene ukáže predlohu
  }

  function showExample(type) {
    // Skry všetky ukážky
    document.querySelectorAll('.example').forEach(el => el.classList.add('hidden'));

    // Zobraz požadovanú
    const example = document.getElementById(`example-${type}`);
    if (example) example.classList.remove('hidden');

    // Resetuj všetky tlačidlá
    document.querySelectorAll('.example-btn').forEach(btn => {
      btn.classList.remove('bg-blue-600', 'text-white');
      btn.classList.add('bg-gray-200', 'text-gray-700');
    });

    // Aktivuj kliknuté tlačidlo
    const activeBtn = Array.from(document.querySelectorAll('.example-btn')).find(btn => btn.onclick.toString().includes(`'${type}'`));
    if (activeBtn) {
      activeBtn.classList.remove('bg-gray-200', 'text-gray-700');
      activeBtn.classList.add('bg-blue-600', 'text-white');
    }
  }
</script>
@endpush

  @stack('scripts')
</body>
</html>

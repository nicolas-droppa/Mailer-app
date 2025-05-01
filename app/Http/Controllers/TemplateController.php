<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function index()
    {
        // Získa všetky šablóny prihláseného používateľa
        $templates = Template::where('user_id', Auth::id())->get();
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        // Zobrazí formulár na vytvorenie novej šablóny
        return view('templates.create');
    }

    public function store(Request $request)
    {
        // Validácia vstupov
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        // Priradí šablóne aktuálneho používateľa a uloží ju
        $validated['user_id'] = Auth::id();
        Template::create($validated);

        return redirect()->route('templates.index')->with('success', 'Šablóna vytvorená');
    }

    public function edit(Template $template)
    {
        // Autorizácia: používateľ môže upravovať iba svoje šablóny
        abort_unless($template->user_id === Auth::id(), 403);
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        abort_unless($template->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        $template->update($validated);

        return redirect()->route('templates.index')->with('success', 'Šablóna aktualizovaná');
    }

    public function destroy(Template $template)
    {
        abort_unless($template->user_id === Auth::id(), 403);
        $template->delete();
        return redirect()->route('templates.index')->with('success', 'Šablóna zmazaná');
    }
}

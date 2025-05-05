<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::where('user_id', Auth::id())->get();
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'subject'    => 'required|string|max:255',
            'body'       => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,gif,pdf|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('templates', 'public');
            $data['attachment_path'] = $path;
        }

        $data['user_id'] = Auth::id();

        Template::create($data);

        return redirect()->route('templates.index')->with('success', 'Šablóna bola uložená.');
    }

    public function edit(Template $template)
    {
        abort_unless($template->user_id === Auth::id(), 403);
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        abort_unless($template->user_id === Auth::id(), 403);

        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'subject'    => 'required|string|max:255',
            'body'       => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,gif,pdf|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            if ($template->attachment_path) {
                Storage::disk('public')->delete($template->attachment_path);
            }

            $path = $request->file('attachment')->store('templates', 'public');
            $data['attachment_path'] = $path;
        }

        $template->update($data);

        return redirect()->route('templates.index')->with('success', 'Šablóna bola aktualizovaná.');
    }

    public function destroy(Template $template)
    {
        abort_unless($template->user_id === Auth::id(), 403);

        if ($template->attachment_path) {
            Storage::disk('public')->delete($template->attachment_path);
        }

        $template->delete();

        return redirect()->route('templates.index')->with('success', 'Šablóna zmazaná.');
    }

    public function copy($id)
    {
        $template = Template::findOrFail($id);
        $newTemplate = $template->replicate();
        $newTemplate->name .= ' - Kópia';
        $newTemplate->save();

        return redirect()->route('templates.index')->with('success', 'Šablóna bola skopírovaná!');
    }

    public function show(Template $template)
    {
        return view('templates.show', compact('template'));
    }
}

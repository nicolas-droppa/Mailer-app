<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::where('user_id', Auth::id())->get();
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        // Validácia
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => [
                'required',
                'email',
                'max:255',
                Rule::unique('contacts')
                    ->where('user_id', Auth::id()),
            ],
            'salutation' => 'required|in:tykanie,vykanie',
            'gender'     => 'required|in:muž,žena',
        ]);

        // Priradíme user_id a uložíme
        $validated['user_id'] = Auth::id();
        Contact::create($validated);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Kontakt bol úspešne pridaný.');
    }

    public function edit(Contact $contact)
    {
        $this->authorizeContact($contact);
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $this->authorizeContact($contact);

        // Validácia (ignorujeme vlastný záznam podľa ID)
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => [
                'required',
                'email',
                'max:255',
                Rule::unique('contacts')
                    ->ignore($contact->id)
                    ->where('user_id', Auth::id()),
            ],
            'salutation' => 'required|in:tykanie,vykanie',
            'gender'     => 'required|in:muž,žena',
        ]);

        $contact->update($validated);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Kontakt bol úspešne upravený.');
    }

    public function destroy(Contact $contact)
    {
        $this->authorizeContact($contact);
        $contact->delete();

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Kontakt bol odstránený.');
    }

    private function authorizeContact(Contact $contact)
    {
        abort_unless($contact->user_id === Auth::id(), 403);
    }
}

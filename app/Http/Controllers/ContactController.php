<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:contacts,email',
            'salutation' => 'required|in:tykanie,vykanie',
            'gender' => 'required|in:muž,žena',
        ]);

        $validated['user_id'] = Auth::id();
        Contact::create($validated);

        return redirect()->route('contacts.index');
    }

    public function edit(Contact $contact)
    {
        $this->authorizeContact($contact);
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $this->authorizeContact($contact);

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
            'salutation' => 'required|in:tykanie,vykanie',
            'gender' => 'required|in:muž,žena',
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index');
    }

    public function destroy(Contact $contact)
    {
        $this->authorizeContact($contact);
        $contact->delete();
        return redirect()->route('contacts.index');
    }

    private function authorizeContact(Contact $contact)
    {
        abort_unless($contact->user_id === Auth::id(), 403);
    }
}
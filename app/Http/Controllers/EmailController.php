<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Template;
use App\Models\Email;
use App\Mail\BulkMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailController extends Controller
{
    public function create()
    {
        $contacts  = Contact::where('user_id', Auth::id())->get();
        $templates = Template::where('user_id', Auth::id())->get();
        return view('emails.create', compact('contacts', 'templates'));
    }

    public function send(Request $r)
    {
        $r->validate([
            'template_id' => 'nullable|exists:templates,id',
            'contact_ids' => 'required|array|min:1',
            'send_option' => 'required|in:now,later',
            'subject'     => 'required|string|max:255',
            'body'        => 'required|string',
            'attachment'  => 'nullable|file|max:10240', // max 10MB
        ]);

        $contacts = Contact::whereIn('id', $r->contact_ids)->get();
        $attachmentPath = null;
        $attachmentPath = $r->input('attachment_path');

        if ($r->hasFile('attachment')) {
            $attachmentPath = $r->file('attachment')->store('attachments', 'public');
        }

        if ($r->send_option === 'now') {
            $recipients = [];

            foreach ($contacts as $contact) {
                $personalizedSubject = $this->parseTemplate($r->subject, $contact);
                $personalizedBody = $this->parseTemplate($r->body, $contact);

                //print_r($personalizedBody);
                //return;

                // Odoslanie e-mailu s personalizovaným predmetom a obsahom
                Mail::to($contact->email)->send(new BulkMail($personalizedSubject, $personalizedBody, $attachmentPath));
                $recipients[] = $contact->email;
            }

            // Uloženie do histórie
            Email::create([
                'user_id'         => Auth::id(),
                'subject'         => $r->subject, // ukladáme originál
                'body'            => $r->body,
                'recipients'      => $recipients,
                'status'          => 'odoslane',
                'attachment_path' => $attachmentPath,
            ]);

            return redirect()->route('emails.history')->with('success', 'E‑maily boli okamžite odoslané.');
        }

        // Príprava na odoslanie neskôr
        Email::create([
            'user_id'         => Auth::id(),
            'subject'         => $r->subject,
            'body'            => $r->body,
            'recipients'      => $contacts->pluck('email')->toArray(),
            'status'          => 'caka',
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('emails.history')->with('success', 'E‑maily boli pripravené na odoslanie.');
    }


    public function parseTemplate(string $template, Contact $contact): string
    {
        // Rozdelenie celého mena na meno a priezvisko
        $nameParts = preg_split('/\s+/', trim($contact->name));
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        // Zistenie oslovenia podľa pohlavia
        $salutation = $contact->salutation;
        $isMale = ($contact->gender === 'muž');
        $isFormal = ($contact->salutation === 'vykanie');
        if ($isMale) {
            if ($salutation === 'vykanie')
                $greeting = 'Dobrý deň pán ' . $lastName;
            else
                $greeting = 'Ahoj ' . $firstName;
        } else {
            if ($salutation === 'vykanie')
                $greeting = 'Dobrý deň pani ' . $lastName;
            else
                $greeting = 'Ahoj ' . $firstName;
        }

        // Určenie správneho tvaru zámen pre pohlavie
        $steSi = $isFormal ? 'ste' : 'si';
        $vasTvoj = $isFormal ? 'váš' : 'tvoj';
        $vamTi = $isFormal ? 'vám' : 'Ti';
        $vasTa = $isFormal ? 'vás' : 'Ťa';
        $vyTy = $isFormal ? 'vy' : 'ty';

        // Pravidlá na nahradenie
        $replacements = [
            '{first_name}}'     => $firstName,
            '{last_name}}'      => $lastName,
            '{name}}'           => $contact->name,
            '{email}}'          => $contact->email,
            '{pozdravenie}'    => $greeting,
            '{ste/si}'         => $steSi,
            '{vas/tvoj}'       => $vasTvoj,
            '{vam/ti}'         => $vamTi,
            '{vas/ta}'         => $vasTa,
            '{vy/ty}'          => $vyTy,
        ];

        // Nahradenie všetkých placeholderov
        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }


    public function history()
    {
        $history = Email::where('user_id', Auth::id())->latest()->get();
        return view('emails.history', compact('history'));
    }

    public function bulkSend(Request $request)
    {
        $ids = $request->input('email_ids', []);
        $emails = Email::whereIn('id', $ids)->where('status', 'caka')->get();

        foreach ($emails as $email) {
            foreach ($email->recipients as $recipient) {
                $contact = Contact::where('email', $recipient)->first();
                if (!$contact) continue;

                $personalizedSubject = $this->parseTemplate($email->subject, $contact);
                $personalizedBody = $this->parseTemplate($email->body, $contact);

                Mail::to($recipient)->send(new BulkMail(
                    $personalizedSubject,
                    $personalizedBody,
                    $email->attachment_path
                ));
            }

            $email->status = 'odoslane';
            $email->save();
        }

        return back()->with('success', 'Vybrané e-maily boli odoslané.');
    }

    public function sendOne(Email $email)
    {
        if ($email->status !== 'caka') {
            return back()->with('error', 'E-mail už bol odoslaný.');
        }

        foreach ($email->recipients as $recipient) {
            $contact = Contact::where('email', $recipient)->first();
            if (!$contact) continue;

            // Personalizované predmet a telo e-mailu pre konkrétny kontakt
            $personalizedSubject = $this->parseTemplate($email->subject, $contact);
            $personalizedBody = $this->parseTemplate($email->body, $contact);

            // Odoslanie e-mailu s personalizovaným predmetom a obsahom
            Mail::to($recipient)->send(new BulkMail(
                $personalizedSubject,
                $personalizedBody,
                $email->attachment_path
            ));
        }

        // Nastavenie stavu e-mailu na "odoslané"
        $email->status = 'odoslane';
        $email->save();

        return back()->with('success', 'E-mail bol úspešne odoslaný.');
    }

    public function edit(Email $email)
    {
        if ($email->status !== 'caka') {
            return redirect()->route('emails.history')->with('error', 'E-mail už bol odoslaný a nie je možné ho upraviť.');
        }

        return view('emails.edit', compact('email'));
    }

    public function update(Request $request, Email $email)
    {
        if ($email->status !== 'caka') {
            return redirect()->route('emails.history')->with('error', 'E-mail už bol odoslaný a nie je možné ho upraviť.');
        }

        $validated = $request->validate([
            'subject'    => 'required|string|max:255',
            'body'       => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf|max:10240', // max 10MB
        ]);

        // Update základných údajov
        $email->update([
            'subject' => $validated['subject'],
            'body'    => $validated['body'],
        ]);

        // Ak je nahraná nová príloha
        if ($request->hasFile('attachment')) {
            // Odstráni starý súbor, ak existuje
            if ($email->attachment_path && \Storage::disk('public')->exists($email->attachment_path)) {
                \Storage::disk('public')->delete($email->attachment_path);
            }

            // Uloženie novej prílohy
            $path = $request->file('attachment')->store('attachments', 'public');

            // Aktualizuj cestu v databáze
            $email->update([
                'attachment_path' => $path,
            ]);
        }

        return redirect()->route('emails.history')->with('success', 'E-mail bol úspešne aktualizovaný.');
    }


    public function copy(Email $email)
    {
        $newEmail = $email->replicate();
        $newEmail->status = 'caka';
        $newEmail->save();

        return redirect()->route('emails.history')->with('success', 'E-mail bol skopírovaný.');
    }

    public function destroy(Email $email)
    {
        if ($email->status !== 'caka') {
            return redirect()->route('emails.history')->with('error', 'Iba čakajúce e-maily je možné vymazať.');
        }

        $email->delete();

        return redirect()->route('emails.history')->with('success', 'E-mail bol úspešne vymazaný.');
    }

    public function show(Email $email)
    {
        if ($email->user_id !== Auth::id()) {
            abort(403);
        }
        return view('emails.show', compact('email'));
    }
}

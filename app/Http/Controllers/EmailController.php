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

        $contacts    = Contact::whereIn('id', $r->contact_ids)->get();
        $recipients  = $contacts->pluck('email')->toArray();
        $attachmentPath = null;
        $subject = $r->subject;  // Použi zadaný predmet
        $body    = $r->body;     // Použi zadané telo

        $attachmentPath = $r->input('attachment_path');

        // Ak je zvolená nová príloha, ulož ju
        if ($r->hasFile('attachment')) {
            $attachmentPath = $r->file('attachment')->store('attachments', 'public');
        } else {
            $attachmentPath = $r->input('attachment_path'); // zvolená cez šablónu
        }

        // Odoslanie e‑mailu
        if ($r->send_option === 'now') {
            foreach ($recipients as $recipient) {
                Mail::to($recipient)->send(new BulkMail($subject, $body, $attachmentPath));
            }

            //dd($r->all(), $attachmentPath);

            // Uloženie do histórie
            Email::create([
                'user_id'        => Auth::id(),
                'subject'        => $subject,
                'body'           => $body,
                'recipients'     => $recipients,
                'status'         => 'odoslane',
                'attachment_path'=> $attachmentPath,
            ]);

            return redirect()->route('emails.history')->with('success', 'E‑maily boli okamžite odoslané.');
        }

        // Ak e‑maily majú byť odoslané neskôr
        Email::create([
            'user_id'        => Auth::id(),
            'subject'        => $subject,
            'body'           => $body,
            'recipients'     => $recipients,
            'status'         => 'caka',
            'attachment_path'=> $attachmentPath,
        ]);

        return redirect()->route('emails.history')->with('success', 'E‑maily boli pripravené na odoslanie.');
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
                // Pass the attachment path to the BulkMail mailable
                Mail::to($recipient)->send(new BulkMail(
                    $email->subject,
                    $email->body,
                    $email->attachment_path // Pass the attachment path
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
            // Pass the attachment path to the BulkMail mailable
            Mail::to($recipient)->send(new BulkMail(
                $email->subject,
                $email->body,
                $email->attachment_path // Pass the attachment path
            ));
        }

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

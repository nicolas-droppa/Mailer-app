<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Template;
use App\Models\Email;
use App\Mail\BulkMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function create()
    {
        $contacts  = Contact::where('user_id',Auth::id())->get();
        $templates = Template::where('user_id',Auth::id())->get();
        return view('emails.create', compact('contacts','templates'));
    }

    public function send(Request $r)
    {
        $r->validate([
            'template_id' => 'nullable|exists:templates,id',
            'contact_ids' => 'required|array|min:1',
            'send_option' => 'required|in:now,later',
            'subject'     => 'required|string|max:255',
            'body'        => 'required|string',
        ]);

        $contacts = Contact::whereIn('id', $r->contact_ids)->get();
        $recipients = $contacts->pluck('email')->toArray();

        // V prípade šablóny prepíš subject/body (iba ak šablóna bola naozaj vybraná)
        if ($r->template_id) {
            $tpl = Template::find($r->template_id);
            $subject = $tpl->subject;
            $body    = $tpl->body;
        } else {
            $subject = $r->subject;
            $body    = $r->body;
        }

        // Odoslať hneď
        if ($r->send_option === 'now') {
            foreach ($recipients as $recipient) {
                Mail::to($recipient)->send(new BulkMail($subject, $body));
            }

            Email::create([
                'user_id'    => Auth::id(),
                'subject'    => $subject,
                'body'       => $body,
                'recipients' => $recipients,
                'status'     => 'odoslane',
            ]);

            return redirect()->route('emails.history')->with('success', 'E‑maily boli okamžite odoslané.');
        }

        // Odoslať neskôr
        Email::create([
            'user_id'    => Auth::id(),
            'subject'    => $subject,
            'body'       => $body,
            'recipients' => $recipients,
            'status'     => 'caka',
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
                Mail::to($recipient)->send(new BulkMail($email->subject, $email->body));
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
            Mail::to($recipient)->send(new BulkMail($email->subject, $email->body));
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
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $email->update($validated);

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
}

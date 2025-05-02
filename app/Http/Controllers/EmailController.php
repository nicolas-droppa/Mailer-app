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
            'template_id' => 'required|exists:templates,id',
            'contact_ids' => 'required|array',
        ]);

        $tpl = Template::find($r->template_id);
        $contacts = Contact::whereIn('id', $r->contact_ids)->get();

        Email::create([
            'user_id'    => Auth::id(),
            'subject'    => $tpl->subject,
            'body'       => $tpl->body,
            'recipients' => $contacts->pluck('email')->toArray(),
            'status'     => 'caka',
        ]);

        return redirect()->route('emails.history')->with('success', 'E-maily boli pripravené na odoslanie.');
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
}

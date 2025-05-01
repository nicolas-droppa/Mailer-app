<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Template;
use App\Models\Email;
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
        $contacts = Contact::whereIn('id',$r->contact_ids)->get();

        foreach($contacts as $c){
            $body = str_replace(
                ['{name}','{salutation}'],
                [$c->name,$c->salutation],
                $tpl->body
            );
            Mail::raw($body, function($m) use($c,$tpl){
                $m->to($c->email)
                  ->subject($tpl->subject);
            });
        }

        Email::create([
            'user_id'    => Auth::id(),
            'subject'    => $tpl->subject,
            'body'       => $tpl->body,
            'recipients' => $contacts->pluck('email')->toArray(),
        ]);

        return redirect()->route('emails.history')->with('success','E-maily odoslané');
    }

    public function history()
    {
        $history = Email::where('user_id',Auth::id())->latest()->get();
        return view('emails.history', compact('history'));
    }
}

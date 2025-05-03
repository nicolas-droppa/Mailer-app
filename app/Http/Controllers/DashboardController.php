<?php
namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Contact;
use App\Models\Template;

class DashboardController extends Controller
{
    public function index()
    {
        $contactCount = Contact::count();
        $templateCount = Template::count();
        $sentCount = Email::where('status', 'odoslane')->count();
        $pendingCount = Email::where('status', 'caka')->count();
        $lastSentEmail = Email::where('status', 'odoslane')->latest('created_at')->first();

        return view('dashboard', compact(
            'contactCount', 
            'templateCount', 
            'pendingCount', 
            'sentCount', 
            'lastSentEmail'
        ));
    }
}

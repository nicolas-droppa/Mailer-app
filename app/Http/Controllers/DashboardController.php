<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Contact;
use App\Models\Template;

class DashboardController extends Controller
{
    public function index()
    {
        // Získaj globálne štatistiky pre celý systém
        $totalContactCount = Contact::count();
        $totalTemplateCount = Template::count();
        $totalSentCount = Email::where('status', 'odoslane')->count();
        $totalPendingCount = Email::where('status', 'caka')->count();

        // Získaj personalizované štatistiky pre prihláseného používateľa
        $user = auth()->user();
        $contactCount = $user->contacts()->count();
        $templateCount = $user->templates()->count();
        $pendingCount = $user->emails()->where('status', 'caka')->count();
        $sentCount = $user->emails()->where('status', 'odoslane')->count();
        $lastSentEmail = $user->emails()->where('status', 'odoslane')->latest('created_at')->first();

        return view('dashboard', compact(
            'totalContactCount',
            'totalTemplateCount',
            'totalSentCount',
            'totalPendingCount',
            'contactCount',
            'templateCount',
            'pendingCount',
            'sentCount',
            'lastSentEmail'
        ));
    }
}

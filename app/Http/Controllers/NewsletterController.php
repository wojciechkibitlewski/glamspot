<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function unsubscribe(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('home')->with('error', 'Nieprawidłowy link wypisania z newslettera.');
        }

        // Znajdź użytkownika w bazie newslettera
        $newsletter = Newsletter::where('email', $email)->first();

        if ($newsletter) {
            // Ustaw newsletter na 'no' zamiast usuwać rekord
            $newsletter->update(['newsletter' => 'no']);
            $message = 'Pomyślnie wypisano z newslettera.';
        } else {
            $message = 'Ten adres email nie znajduje się w bazie newslettera.';
        }

        return view('sites.newsletter.unsubscribe', [
            'email' => $email,
            'message' => $message,
            'wasSubscribed' => (bool) $newsletter
        ]);
    }
}

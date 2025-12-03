<?php

namespace App\Livewire;

use App\Mail\NewsletterWelcome;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;

class NewsletterSubscribe extends Component
{
    #[Validate('required|email|max:255')]
    public string $email = '';

    public bool $showModal = false;

    public function subscribe()
    {
        $this->validate();

        // Sprawdź czy email już istnieje w bazie
        $newsletter = Newsletter::where('email', $this->email)->first();

        if ($newsletter) {
            // Jeśli email już istnieje, po prostu zaktualizuj status na 'yes'
            $newsletter->update(['newsletter' => 'yes']);
        } else {
            // Utwórz nowy wpis
            Newsletter::create([
                'email' => $this->email,
                'newsletter' => 'yes',
            ]);
        }

        // Wyślij email powitalny
        Mail::to($this->email)->send(new NewsletterWelcome($this->email));

        // Pokaż modal z potwierdzeniem
        $this->showModal = true;

        // Wyczyść pole email
        $this->email = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.newsletter-subscribe');
    }
}

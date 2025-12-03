<?php

namespace App\Livewire\User;

use App\Models\Newsletter;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewsletterSettings extends Component
{
    public bool $newsletter = false;
    public bool $works = false;
    public bool $courses = false;
    public bool $devices = false;

    public bool $showSuccessMessage = false;

    public function mount()
    {
        $user = Auth::user();

        // Pobierz istniejące ustawienia newslettera dla użytkownika
        $newsletterSettings = Newsletter::where('email', $user->email)->first();

        if ($newsletterSettings) {
            $this->newsletter = $newsletterSettings->newsletter === 'yes';
            $this->works = $newsletterSettings->works === 'yes';
            $this->courses = $newsletterSettings->courses === 'yes';
            $this->devices = $newsletterSettings->devices === 'yes';
        }
    }

    public function save()
    {
        $user = Auth::user();

        // Utwórz lub zaktualizuj ustawienia newslettera
        Newsletter::updateOrCreate(
            ['email' => $user->email],
            [
                'user_id' => $user->id,
                'newsletter' => $this->newsletter ? 'yes' : 'no',
                'works' => $this->works ? 'yes' : 'no',
                'courses' => $this->courses ? 'yes' : 'no',
                'devices' => $this->devices ? 'yes' : 'no',
            ]
        );

        $this->showSuccessMessage = true;

        // Schowaj wiadomość po 3 sekundach
        $this->dispatch('success-saved');
    }

    public function render()
    {
        return view('livewire.user.newsletter-settings');
    }
}

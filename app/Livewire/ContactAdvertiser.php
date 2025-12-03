<?php

namespace App\Livewire;

use App\Mail\ContactAdvertiser as ContactAdvertiserMail;
use App\Models\Ad;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactAdvertiser extends Component
{
    public int $adId;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    public bool $terms = false;

    public bool $newsletter = false;

    public bool $sent = false;

    public function mount(int $adId): void
    {
        $this->adId = $adId;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'min:5'],
            'terms' => ['accepted'],
            'newsletter' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'terms.accepted' => __('ads.contact.form.terms_required'),
        ];
    }

    public function send(): void
    {
        $this->validate();

        $ad = Ad::query()
            ->with(['user', 'category'])
            ->where('status', 'active')
            ->whereKey($this->adId)
            ->whereNotNull('published_at')
            ->whereNotNull('expires_at')
            ->where('published_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->firstOrFail();

        $contactEmail = $ad->as_company ? ($ad->company_email ?? null) : ($ad->person_email ?? null);
        if (! $contactEmail) {
            $contactEmail = $ad->user?->email;
        }

        if (! $contactEmail) {
            $this->addError('email', __('ads.contact.no_mail'));

            return;
        }

        $mailable = new ContactAdvertiserMail(
            ad: $ad,
            fromName: $this->name,
            fromEmail: $this->email,
            phone: $this->phone,
            body: $this->message,
        );

        $mailable->replyTo($this->email, $this->name);

        Mail::to($contactEmail)->send($mailable);

        // Zapisz do newslettera jeśli zaznaczono checkbox
        if ($this->newsletter) {
            $this->subscribeToNewsletter($ad);
        }

        $this->sent = true;
        $this->reset(['name', 'email', 'phone', 'message', 'terms', 'newsletter']);
    }

    protected function subscribeToNewsletter(Ad $ad): void
    {
        // Sprawdź kategorię ogłoszenia na podstawie slug
        $categorySlug = $ad->category->slug ?? null;

        // Mapowanie kategorii do pól w tabeli newsletters
        $categoryField = match ($categorySlug) {
            'praca' => 'works',
            'szkolenia' => 'courses',
            'urzadzenia-i-sprzet' => 'devices',
            default => null,
        };

        // Jeśli kategoria nie pasuje do żadnej z obsługiwanych, zakończ
        if (! $categoryField) {
            return;
        }

        // Sprawdź czy użytkownik jest zalogowany
        $userId = Auth::check() ? Auth::id() : null;

        // Sprawdź czy email już istnieje w bazie
        $newsletter = Newsletter::query()->where('email', $this->email)->first();

        if ($newsletter) {
            // Aktualizuj istniejący wpis - ustaw odpowiednie pole na 'yes'
            $newsletter->update([
                $categoryField => 'yes',
                'newsletter' => 'yes',
            ]);
        } else {
            // Utwórz nowy wpis
            Newsletter::create([
                'email' => $this->email,
                'user_id' => $userId,
                'newsletter' => 'yes',
                $categoryField => 'yes',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.contact-advertiser');
    }
}

<?php

namespace App\Livewire\Auth;

use App\Livewire\Actions\Logout;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class VerifyEmail extends Component
{
    public function sendVerification(): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        if ($user instanceof MustVerifyEmail && $user->hasVerifiedEmail()) {
            $this->redirectIntended(route('post-register.choice', absolute: false), navigate: true);
            return;
        }

        if ($user instanceof MustVerifyEmail) {
            $user->sendEmailVerificationNotification();
            Session::flash('status', 'verification-link-sent');
        } else {
            Session::flash('status', 'verification-not-required');
        }
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}

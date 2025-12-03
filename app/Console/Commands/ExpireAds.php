<?php

namespace App\Console\Commands;

use App\Mail\AdExpired;
use App\Models\Ad;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ExpireAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire ads that have passed their expiration date and notify users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for expired ads...');

        // Znajdź wszystkie aktywne ogłoszenia, których expires_at jest w przeszłości
        $expiredAds = Ad::with('user')
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        $count = $expiredAds->count();

        if ($count === 0) {
            $this->info('No ads to expire.');
            return self::SUCCESS;
        }

        $emailsSent = 0;

        // Dla każdego ogłoszenia: zaktualizuj status i wyślij email
        foreach ($expiredAds as $ad) {
            // Zaktualizuj status
            $ad->update(['status' => 'expired']);

            // Wyślij email do właściciela ogłoszenia
            if ($ad->user?->email) {
                try {
                    Mail::to($ad->user->email)->send(new AdExpired($ad));
                    $emailsSent++;
                } catch (\Exception $e) {
                    $this->error("Failed to send email for ad {$ad->getAttribute('code')}: " . $e->getMessage());
                }
            }
        }

        $this->info("Successfully expired {$count} ad(s).");
        $this->info("Sent {$emailsSent} notification email(s).");

        return self::SUCCESS;
    }
}

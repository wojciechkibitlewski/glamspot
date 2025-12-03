<?php

namespace App\Livewire\Ads;

use App\Models\AdPhoto;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class Photos extends Component
{
    use WithFileUploads;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    #[Validate(['photos.*' => 'mimes:jpg,jpeg,png,webp|max:10240'])]
    public array $photos = [];

    /** @var list<string> */
    public array $queued = [];

    /** @var list<string> Existing stored paths like 'ad-photos/xyz.jpg' */
    public array $existing = [];

    public ?int $adId = null;

    public bool $saved = false;

    public function mount(array $existing = [], ?int $adId = null): void
    {
        $this->existing = array_values($existing);
        $this->adId = $adId;
        $this->queued = Session::get('ad_photos_pending', []);
    }

    public function updatedPhotos(): void
    {
        $this->validateOnly('photos');

        if (count($this->queued) + count($this->photos) > 10) {
            $this->addError('photos', __('Możesz dodać maksymalnie 10 zdjęć.'));
            $this->photos = [];

            return;
        }

        foreach ($this->photos as $file) {
            $path = $file->store('ad-photos', 'public');

            try {
                OptimizerChainFactory::create()->optimize(storage_path('app/public/'.$path));
            } catch (\Throwable $e) {
                // ignore optimizer failures
            }

            $this->queued[] = $path;
        }

        Session::put('ad_photos_pending', $this->queued);
        $this->photos = [];
        $this->saved = true;
    }

    public function remove(string $path): void
    {
        $this->queued = array_values(array_filter($this->queued, fn ($p) => $p !== $path));
        Session::put('ad_photos_pending', $this->queued);

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $this->saved = true;
    }

    public function removeExisting(string $path): void
    {
        // Update UI list first
        $this->existing = array_values(array_filter($this->existing, fn ($p) => $p !== $path));

        // Delete DB record if we have context
        if ($this->adId) {
            $record = AdPhoto::query()
                ->where('ad_id', $this->adId)
                ->where('photo', $path)
                ->first();

            if ($record) {
                $record->delete();
            }
        }

        // Delete file from storage if exists
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.ads.photos');
    }
}

<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class UpdateProfile extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:32')]
    public ?string $phone = null;

    #[Validate('nullable|string|max:86')]
    public ?string $city = null;

    // Avatar upload (temporary)
    #[Validate('nullable|mimes:jpg,jpeg,png,webp|max:10240')]
    public $avatarUpload = null; // TemporaryUploadedFile | null

    public ?string $currentAvatar = null; // stored path like 'avatars/xyz.jpg'

    public bool $saved = false;

    public function mount(): void
    {
        $user = Auth::user();
        if ($user) {
            $this->name = (string) $user->name;
            $this->phone = $user->phone ?? null;
            $this->city = $user->city ?? null;
            $this->currentAvatar = $user->avatar ?? null;
        }
    }

    public function save(): void
    {
        $this->validate();

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (! $user) {
            return;
        }

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'city' => $this->city,
        ];

        $user->forceFill($data)->save();

        $this->saved = true;
    }

    /**
     * Immediately handle avatar upload (drag&drop or file select).
     */
    public function updatedAvatarUpload(): void
    {
        // Validate only the avatar field
        $this->validateOnly('avatarUpload');

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (! $user || ! $this->avatarUpload) {
            return;
        }

        // Store in public disk under avatars/
        $path = $this->avatarUpload->store('avatars', 'public');

        // Optimize image using Spatie Image Optimizer
        try {
            $absolute = storage_path('app/public/'.$path);
            OptimizerChainFactory::create()->optimize($absolute);
        } catch (\Throwable $e) {
            // Ignore optimizer failures; keep the stored file
        }

        // Remove previous avatar if exists
        if ($this->currentAvatar && Storage::disk('public')->exists($this->currentAvatar)) {
            Storage::disk('public')->delete($this->currentAvatar);
        }

        // Persist on user immediately
        $user->forceFill(['avatar' => $path])->save();

        // Sync component state
        $this->currentAvatar = $path;
        $this->avatarUpload = null;
        $this->saved = true;
    }

    public function removeAvatar(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (! $user) {
            return;
        }

        if ($this->currentAvatar && Storage::disk('public')->exists($this->currentAvatar)) {
            Storage::disk('public')->delete($this->currentAvatar);
        }

        $user->forceFill(['avatar' => null])->save();
        $this->currentAvatar = null;
        $this->avatarUpload = null;
        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.user.update-profile');
    }
}

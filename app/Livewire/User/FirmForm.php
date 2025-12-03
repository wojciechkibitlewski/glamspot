<?php

namespace App\Livewire\User;

use App\Models\City;
use App\Models\Firm;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class FirmForm extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $firm_name = '';

    #[Validate('nullable|string|max:255')]
    public ?string $firm_city = null;

    #[Validate('nullable|string|max:32')]
    public ?string $firm_postalcode = null;

    #[Validate('nullable|string|max:255')]
    public ?string $firm_address = null;

    #[Validate('required|integer|exists:regions,id')]
    public ?int $firm_region_id = null;

    #[Validate('nullable|string|max:32')]
    public ?string $firm_nip = null;

    #[Validate('nullable|url|max:255')]
    public ?string $firm_www = null;

    #[Validate('nullable|email|max:255')]
    public ?string $firm_email = null;

    #[Validate('nullable|string|max:255')]
    public ?string $firm_logo = null;

    // Avatar upload (temporary)
    #[Validate('nullable|mimes:jpg,jpeg,png,webp|max:10240')]
    public $avatarUpload = null; // TemporaryUploadedFile | null

    public ?string $currentAvatar = null;

    public bool $saved = false;

    public function mount(): void
    {
        $firm = Auth::user()?->firm;
        if ($firm) {
            foreach ([
                'firm_name', 'firm_city', 'firm_postalcode', 'firm_address', 'firm_nip', 'firm_www', 'firm_email', 'firm_logo',
            ] as $field) {
                $this->{$field} = $firm->{$field};
            }
            $this->firm_region_id = $firm->firm_region_id;
            $this->currentAvatar = $firm->avatar;
        }
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();
        if (! $user) {
            return;
        }

        Firm::updateOrCreate(
            ['user_id' => $user->id],
            [
                'firm_name' => $this->firm_name,
                'firm_city' => $this->firm_city,
                'firm_postalcode' => $this->firm_postalcode,
                'firm_address' => $this->firm_address,
                'firm_region' => optional(Region::find($this->firm_region_id))->name,
                'firm_region_id' => $this->firm_region_id,
                'firm_nip' => $this->firm_nip,
                'firm_www' => $this->firm_www,
                'firm_email' => $this->firm_email,
                'firm_logo' => $this->firm_logo,
                'avatar' => $this->currentAvatar,
            ]
        );

        // Ensure city exists in our cities table (scoped by chosen region)
        $this->ensureCityExists($this->firm_city, $this->firm_region_id);

        $this->saved = true;
        // Close modal (if rendered inside one) and notify page to refresh
        $this->dispatch('modal-close', name: 'add-firm');
        $this->dispatch('firm-saved');
    }

    public function render()
    {
        return view('livewire.user.firm-form');
    }

    public function updatedAvatarUpload(): void
    {
        $this->validateOnly('avatarUpload');

        $user = Auth::user();
        if (! $user || ! $this->avatarUpload) {
            return;
        }

        $path = $this->avatarUpload->store('firm-avatars', 'public');

        try {
            OptimizerChainFactory::create()->optimize(storage_path('app/public/'.$path));
        } catch (\Throwable $e) {
            // ignore optimizer errors
        }

        if ($this->currentAvatar && Storage::disk('public')->exists($this->currentAvatar)) {
            Storage::disk('public')->delete($this->currentAvatar);
        }

        $this->currentAvatar = $path;
        $this->avatarUpload = null;
        $this->saved = true;
    }

    protected function ensureCityExists(?string $cityName, ?int $regionId): void
    {
        $name = trim((string) ($cityName ?? ''));
        if ($name === '' || ! $regionId) {
            return;
        }

        $region = Region::query()->find($regionId);
        if (! $region) {
            return;
        }

        $slug = Str::slug($name);
        $exists = City::query()
            ->where('region_id', $region->id)
            ->where('slug', $slug)
            ->exists();
        if (! $exists) {
            City::create([
                'region_id' => $region->id,
                'name' => $name,
                'slug' => $slug,
            ]);
        }
    }
}

<?php

namespace App\Livewire\Blog;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class FeaturedImage extends Component
{
    use WithFileUploads;

    #[Validate(['image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240'])]
    public $image;

    public $existingImage = null;
    public $tempImagePath = null;

    public function mount($existing = null)
    {
        $this->existingImage = $existing;
    }

    public function updatedImage()
    {
        $this->validate();

        if ($this->image) {
            // Store the image temporarily
            $path = $this->image->store('blog-featured-images', 'public');

            // Optimize the image
            try {
                OptimizerChainFactory::create()->optimize(storage_path('app/public/' . $path));
            } catch (\Throwable $e) {
                // Ignore optimizer failures
            }

            // Remove old temporary image if exists
            if ($this->tempImagePath) {
                Storage::disk('public')->delete($this->tempImagePath);
            }

            $this->tempImagePath = $path;

            // Store in session for later retrieval
            session(['blog_featured_image_pending' => $path]);

            $this->dispatch('featured-image-uploaded', path: $path);
        }
    }

    public function remove()
    {
        if ($this->tempImagePath) {
            Storage::disk('public')->delete($this->tempImagePath);
            $this->tempImagePath = null;
            session()->forget('blog_featured_image_pending');
            $this->reset('image');
        }
    }

    public function removeExisting()
    {
        if ($this->existingImage) {
            $this->existingImage = null;
            $this->dispatch('existing-image-removed');
        }
    }

    public function render()
    {
        return view('livewire.blog.featured-image');
    }
}

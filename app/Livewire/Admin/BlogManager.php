<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class BlogManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterPublished = 'all'; // all, published, draft

    protected $queryString = ['search', 'filterPublished'];

    public function render()
    {
        $posts = Post::query()
            ->with(['category'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('lead', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterPublished === 'published', function ($query) {
                $query->where('is_published', true);
            })
            ->when($this->filterPublished === 'draft', function ($query) {
                $query->where('is_published', false);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-16'],
            ['key' => 'title', 'label' => 'Tytuł'],
            ['key' => 'category', 'label' => 'Kategoria'],
            ['key' => 'is_published', 'label' => 'Status'],
            ['key' => 'published_at', 'label' => 'Data publikacji'],
            ['key' => 'actions', 'label' => 'Akcje', 'class' => 'text-right w-40'],
        ];

        return view('livewire.admin.blog-manager', [
            'posts' => $posts,
            'headers' => $headers,
        ]);
    }

    public function delete($postId)
    {
        $post = Post::findOrFail($postId);
        $post->delete();

        session()->flash('message', 'Artykuł został usunięty.');
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterPublished()
    {
        $this->resetPage();
    }
}

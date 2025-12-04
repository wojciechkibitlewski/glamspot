<?php

namespace App\Livewire\Admin;

use App\Models\Ad;
use Livewire\Component;
use Livewire\WithPagination;

class AdManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all'; // all, active, pending_payment, expired, inactive

    protected $queryString = ['search', 'filterStatus'];

    public function render()
    {
        $ads = Ad::query()
            ->with(['category', 'user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('location', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterStatus === 'active', function ($query) {
                $query->where('status', 'active');
            })
            ->when($this->filterStatus === 'pending_payment', function ($query) {
                $query->where('status', 'pending_payment');
            })
            ->when($this->filterStatus === 'expired', function ($query) {
                $query->where('status', 'expired');
            })
            ->when($this->filterStatus === 'inactive', function ($query) {
                $query->where('status', 'inactive');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-16'],
            ['key' => 'title', 'label' => 'Tytuł'],
            ['key' => 'category', 'label' => 'Kategoria'],
            ['key' => 'user', 'label' => 'Użytkownik'],
            ['key' => 'location', 'label' => 'Lokalizacja'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => 'Data utworzenia'],
            ['key' => 'actions', 'label' => 'Akcje', 'class' => 'text-right w-40'],
        ];

        return view('livewire.admin.ad-manager', [
            'ads' => $ads,
            'headers' => $headers,
        ]);
    }

    public function deactivate($adId)
    {
        $ad = Ad::findOrFail($adId);
        $ad->status = 'inactive';
        $ad->save();

        session()->flash('message', 'Ogłoszenie zostało dezaktywowane.');
        $this->resetPage();
    }

    public function activate($adId)
    {
        $ad = Ad::findOrFail($adId);
        $ad->status = 'active';
        $ad->published_at = now();
        $ad->expires_at = now()->addDays(30);
        $ad->save();

        session()->flash('message', 'Ogłoszenie zostało aktywowane na 30 dni.');
        $this->resetPage();
    }

    public function delete($adId)
    {
        $ad = Ad::findOrFail($adId);

        // Delete related records
        if ($ad->job) {
            $ad->job->delete();
        }
        if ($ad->machines) {
            $ad->machines->delete();
        }
        if ($ad->training) {
            $ad->training->delete();
        }
        if ($ad->photos) {
            foreach ($ad->photos as $photo) {
                $photo->delete();
            }
        }

        $ad->delete();

        session()->flash('message', 'Ogłoszenie zostało usunięte.');
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class UserManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editMode = false;
    public $userId = null;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $city = '';
    public $phone = '';

    public $search = '';

    // public array $sortBy = '';


    protected $queryString = ['search'];

    public function render()
    {
        $users = User::query()
            ->withCount('ads')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-16'],
            ['key' => 'name', 'label' => 'Imię i nazwisko'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'city', 'label' => 'Miasto'],
            ['key' => 'email_verified_at', 'label' => 'Aktywny'],
            ['key' => 'ads_count', 'label' => 'Ogłoszenia', 'class' => 'text-center'],
            ['key' => 'actions', 'label' => 'Akcje', 'class' => 'text-right w-40'],
        ];

        return view('livewire.admin.user-manager', [
            'users' => $users,
            'headers' => $headers,
        ]);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($userId)
    {
        $this->resetForm();
        $user = User::findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->city = $user->city ?? '';
        $this->phone = $user->phone ?? '';

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            if ($this->editMode) {
                $this->update();
            } else {
                $this->create();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Wystąpił błąd: ' . $e->getMessage());
            Log::error('UserManager save error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }
    }

    protected function create()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'city' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'city' => $validated['city'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email_verified_at' => now(),
        ]);

        $this->closeModal();
        $this->dispatch('user-created');
        session()->flash('message', 'Użytkownik został dodany pomyślnie.');
        $this->resetPage();
    }

    protected function update()
    {
        Log::info('Update method called', ['userId' => $this->userId, 'name' => $this->name, 'email' => $this->email]);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->userId],
            'city' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        if (!empty($this->password)) {
            $rules['password'] = ['confirmed', Password::defaults()];
        }

        $validated = $this->validate($rules);

        Log::info('Validation passed', ['validated' => $validated]);

        $user = User::findOrFail($this->userId);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->city = $validated['city'] ?? null;
        $user->phone = $validated['phone'] ?? null;

        if (!empty($this->password)) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        Log::info('User saved successfully', ['userId' => $user->id]);

        $this->closeModal();
        $this->dispatch('user-updated');
        session()->flash('message', 'Dane użytkownika zostały pomyślnie zaktualizowane.');
    }

    public function delete($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        session()->flash('message', 'Użytkownik został usunięty.');
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->city = '';
        $this->phone = '';
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RolesAdminController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()
            ->orderBy('name')
            ->paginate(15);

        return view('sites.admin.roles.index', [
            'roles' => $roles,
        ]);
    }

    public function create(): View
    {
        return view('sites.admin.roles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRole($request);

        Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('message', 'Rola została dodana.');
    }

    public function edit(Role $role): View
    {
        return view('sites.admin.roles.edit', [
            'role' => $role,
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $this->validateRole($request, $role->id);

        $role->update([
            'name' => $data['name'],
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('message', 'Rola została zaktualizowana.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if (method_exists($role, 'users') && $role->users()->exists()) {
            return back()->withErrors('Nie można usunąć roli przypisanej do użytkowników.');
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('message', 'Rola została usunięta.');
    }

    protected function validateRole(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($ignoreId),
            ],
        ]);
    }
}

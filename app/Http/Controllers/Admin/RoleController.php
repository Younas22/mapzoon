<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Role::class);

        $permissionGroups = Permission::query()
            ->orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');

        return view('admin.roles.index', [
            'roles' => $this->paginateRoles($request),
            'permissionGroups' => $permissionGroups,
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Role::class);

        $roles = $this->paginateRoles($request);

        return response()->json([
            'html' => view('admin.roles.partials.table', compact('roles'))->render(),
        ]);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::query()->create([
            'name' => $request->validated('name'),
            'slug' => Str::slug($request->validated('name')),
            'description' => $request->validated('description'),
        ]);

        $role->permissions()->sync($request->validated('permissions', []));

        return response()->json([
            'message' => 'Role created successfully.',
        ], 201);
    }

    public function edit(Role $role): JsonResponse
    {
        $this->authorize('view', $role);

        return response()->json([
            'role' => $role->only('id', 'name', 'description', 'is_system'),
            'permission_ids' => $role->permissions()->pluck('permissions.id'),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role->update([
            'name' => $request->validated('name'),
            'slug' => Str::slug($request->validated('name')),
            'description' => $request->validated('description'),
        ]);

        $role->permissions()->sync($request->validated('permissions', []));

        return response()->json([
            'message' => 'Role updated successfully.',
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);

        if ($role->users()->exists()) {
            return response()->json([
                'message' => 'This role is assigned to one or more users and cannot be deleted.',
            ], 422);
        }

        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully.',
        ]);
    }

    protected function paginateRoles(Request $request)
    {
        $sort = in_array($request->query('sort'), ['name', 'created_at']) ? $request->query('sort') : 'name';
        $dir = $request->query('dir') === 'desc' ? 'desc' : 'asc';

        return Role::query()
            ->withCount(['users', 'permissions'])
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->query('q').'%'))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();
    }
}

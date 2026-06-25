<x-admin-layout title="Add User">
    @include('admin.users._form', [
        'user' => $user,
        'roles' => $roles,
        'permissionGroups' => $permissionGroups,
        'selectedPermissionIds' => $selectedPermissionIds,
        'action' => route('admin.users.store'),
        'method' => 'POST',
        'submitLabel' => 'Create User',
    ])
</x-admin-layout>

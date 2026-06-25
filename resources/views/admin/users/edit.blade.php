<x-admin-layout title="Edit User">
    @include('admin.users._form', [
        'user' => $user,
        'roles' => $roles,
        'permissionGroups' => $permissionGroups,
        'selectedPermissionIds' => $selectedPermissionIds,
        'action' => route('admin.users.update', $user),
        'method' => 'PUT',
        'submitLabel' => 'Save Changes',
    ])
</x-admin-layout>

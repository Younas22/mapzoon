@php
    $selectedIdsAsStrings = array_map('strval', $selectedPermissionIds);
@endphp

<div
    x-data="userForm(@js(['avatarUrl' => $user->avatarUrl(), 'permissionIds' => $selectedIdsAsStrings]))"
    class="mx-auto max-w-3xl"
>
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($method === 'PUT')
            @method('PUT')
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Profile Photo</h2>

            <div class="flex items-center gap-4">
                <template x-if="avatarPreview">
                    <img :src="avatarPreview" class="h-16 w-16 rounded-full object-cover" alt="Avatar preview">
                </template>
                <template x-if="!avatarPreview">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 text-xl font-semibold text-primary-700">
                        {{ Str::upper(Str::substr($user->name ?: '?', 0, 1)) }}
                    </span>
                </template>

                <div>
                    <input type="file" name="avatar" accept="image/*" @change="onAvatarChange($event)"
                           class="block text-sm text-slate-600">
                    <p class="mt-1 text-xs text-slate-400">JPG or PNG, up to 2MB.</p>
                    @error('avatar')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Basic Information</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('phone')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->id ? $user->is_active : true))
                               class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        Active account
                    </label>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Department</label>
                    <input type="text" name="department" value="{{ old('department', $user->department) }}" placeholder="e.g. SEO, Development, Sales"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('department')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Designation</label>
                    <input type="text" name="designation" value="{{ old('designation', $user->designation) }}" placeholder="e.g. SEO Specialist"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('designation')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Security</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">
                        Password @if ($user->id) <span class="text-xs text-slate-400">(leave blank to keep current)</span> @endif
                    </label>
                    <input type="password" name="password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('password')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Role</h2>

            <select name="role_id" class="w-full max-w-sm rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                <option value="">No role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role_id')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-1 text-base font-semibold text-ink">Individual Permissions</h2>
            <p class="mb-4 text-sm text-slate-500">Extra permissions granted to this user on top of their role.</p>
            @include('admin.partials.permission-checkboxes', ['permissionGroups' => $permissionGroups])
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ $user->id ? route('admin.users.show', $user) : route('admin.users.index') }}"
               class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">
                Cancel
            </a>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</div>

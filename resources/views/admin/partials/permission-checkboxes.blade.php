{{-- Expects $permissionGroups (Permission models grouped by module). Binds to the surrounding Alpine component's `form.permissions` array. --}}
<div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
    @foreach ($permissionGroups as $module => $permissions)
        <div class="rounded-xl border border-slate-200 p-3">
            <div class="mb-2 flex items-center justify-between">
                <span class="text-sm font-semibold text-ink">{{ Str::headline($module) }}</span>
                <label class="flex items-center gap-1.5 text-xs text-slate-500">
                    <input
                        type="checkbox"
                        class="rounded border-slate-300 text-primary-600 focus:ring-primary-500"
                        :checked="isGroupFullyChecked(@js($permissions->pluck('id')->all()))"
                        @change="toggleGroup(@js($permissions->pluck('id')->all()), $event.target.checked)"
                    >
                    All
                </label>
            </div>

            <div class="space-y-1.5">
                @foreach ($permissions as $permission)
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->id }}"
                            x-model="form.permissions"
                            class="rounded border-slate-300 text-primary-600 focus:ring-primary-500"
                        >
                        {{ Str::headline(Str::after($permission->slug, '.')) }}
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

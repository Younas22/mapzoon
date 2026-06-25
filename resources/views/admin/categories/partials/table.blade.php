<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[640px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Description</th>
                <th class="px-4 py-3">Posts</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($categories as $category)
                <tr>
                    <td class="px-4 py-3 font-medium text-ink">{{ $category->name }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $category->description ?: '—' }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $category->posts_count }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEdit({{ $category->id }})">Edit</button>
                            <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700" @click="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}')">Delete</button>
                        </div>
                    </td>
                </tr>
                @foreach ($category->children as $child)
                    <tr class="bg-slate-50/60">
                        <td class="px-4 py-3 pl-10 font-medium text-ink">
                            <span class="mr-1 text-slate-400">&#8627;</span> {{ $child->name }}
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $child->description ?: '—' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $child->posts_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-3">
                                <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEdit({{ $child->id }})">Edit</button>
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700" @click="confirmDelete({{ $child->id }}, '{{ addslashes($child->name) }}')">Delete</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-10 text-center text-slate-400">No categories yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

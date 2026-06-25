@php
    $selectedTagIdsAsStrings = array_map('strval', $selectedTagIds);
    $faqsForJs = $post->faqs?->map(fn ($faq) => ['question' => $faq->question, 'answer' => $faq->answer])->all() ?? [];
@endphp

<div
    x-data="blogPostForm(@js([
        'content' => $post->content ?: [],
        'faqs' => $faqsForJs,
        'featuredImageUrl' => $post->featuredImageUrl(),
        'slugTouched' => (bool) $post->id,
        'status' => $post->status ?: 'draft',
    ]))"
    class="mx-auto max-w-5xl"
>
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($method === 'PUT')
            @method('PUT')
        @endif

        {{-- Basic Information --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Basic Information</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Title</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" @input="onTitleInput($event)"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('title')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $post->slug) }}" x-ref="slugInput" @input="onSlugInput()"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('slug')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Category</label>
                    <select name="category_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>
                            @foreach ($category->children as $child)
                                <option value="{{ $child->id }}" @selected(old('category_id', $post->category_id) == $child->id)>&nbsp;&nbsp;&#8627; {{ $child->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Author</label>
                    <select name="author_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}" @selected(old('author_id', $post->author_id ?: auth()->id()) == $author->id)>{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" x-model="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @foreach (\App\Models\BlogPost::STATUSES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="status === 'scheduled'" x-cloak>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Publish Date &amp; Time</label>
                    <input type="datetime-local" name="published_at"
                           value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('published_at')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))
                               class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        Homepage Featured
                    </label>
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Excerpt</label>
                    <textarea name="excerpt" rows="2"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Featured Image</label>
                    <div class="flex items-center gap-4">
                        <template x-if="featuredImagePreview">
                            <img :src="featuredImagePreview" class="h-20 w-32 rounded-lg object-cover" alt="Featured image preview">
                        </template>
                        <template x-if="!featuredImagePreview">
                            <span class="flex h-20 w-32 items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-400">No image</span>
                        </template>
                        <input type="file" name="featured_image" accept="image/*" @change="onFeaturedImageChange($event)" class="text-sm text-slate-600">
                    </div>
                    @error('featured_image')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Tags</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($tags as $tag)
                            <label class="flex items-center gap-1.5 rounded-full border border-slate-200 px-3 py-1.5 text-sm text-slate-600">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                       @checked(in_array((string) $tag->id, old('tags', $selectedTagIdsAsStrings)))
                                       class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                {{ $tag->name }}
                            </label>
                        @endforeach
                        @if ($tags->isEmpty())
                            <p class="text-sm text-slate-400">No tags yet — create some from the Tags page.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Content Editor --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-1 text-base font-semibold text-ink">Content Editor</h2>
            <p class="mb-4 text-sm text-slate-500">Build the article body block by block — this renders using the same design as the live MAPZOON blog.</p>

            <div class="mb-4 flex flex-wrap gap-2">
                <button type="button" @click="addBlock('paragraph')" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">+ Paragraph</button>
                <button type="button" @click="addBlock('heading')" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">+ Heading</button>
                <button type="button" @click="addBlock('list')" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">+ List</button>
                <button type="button" @click="addBlock('quote')" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">+ Quote</button>
                <button type="button" @click="addBlock('table')" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">+ Table</button>
                <button type="button" @click="addBlock('image')" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">+ Image</button>
            </div>

            <template x-if="content.length === 0">
                <p class="rounded-lg bg-slate-50 px-4 py-6 text-center text-sm text-slate-400">No content blocks yet. Add one above to start writing.</p>
            </template>

            <template x-for="(block, index) in content" :key="index">
                <div class="mb-3 rounded-xl border border-slate-200 p-4">
                    <input type="hidden" :name="'content['+index+'][type]'" :value="block.type">

                    <div class="mb-3 flex items-center justify-between">
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold uppercase tracking-wide text-slate-500" x-text="block.type"></span>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="moveBlock(index, -1)" class="text-slate-400 hover:text-ink">&uarr;</button>
                            <button type="button" @click="moveBlock(index, 1)" class="text-slate-400 hover:text-ink">&darr;</button>
                            <button type="button" @click="removeBlock(index)" class="text-xs font-medium text-rose-600 hover:text-rose-700">Remove</button>
                        </div>
                    </div>

                    <template x-if="block.type === 'paragraph' || block.type === 'heading'">
                        <textarea :name="'content['+index+'][text]'" x-model="block.text" rows="3"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"
                                  placeholder="Block text"></textarea>
                    </template>

                    <template x-if="block.type === 'quote'">
                        <div class="space-y-2">
                            <textarea :name="'content['+index+'][text]'" x-model="block.text" rows="2" placeholder="Quote text"
                                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                            <input :name="'content['+index+'][cite]'" x-model="block.cite" placeholder="Citation (optional)"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>
                    </template>

                    <template x-if="block.type === 'list'">
                        <div class="space-y-2">
                            <template x-for="(item, itemIndex) in block.items" :key="itemIndex">
                                <div class="flex gap-2">
                                    <input :name="'content['+index+'][items]['+itemIndex+']'" x-model="block.items[itemIndex]"
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                    <button type="button" @click="removeListItem(index, itemIndex)" class="text-rose-600">&times;</button>
                                </div>
                            </template>
                            <button type="button" @click="addListItem(index)" class="text-xs font-semibold text-primary-600 hover:text-primary-700">+ Add Item</button>
                        </div>
                    </template>

                    <template x-if="block.type === 'table'">
                        <div class="space-y-2 overflow-x-auto">
                            <div class="flex gap-2">
                                <template x-for="(header, colIndex) in block.headers" :key="colIndex">
                                    <div class="flex min-w-[140px] flex-1 gap-1">
                                        <input :name="'content['+index+'][headers]['+colIndex+']'" x-model="block.headers[colIndex]"
                                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                        <button type="button" @click="removeTableColumn(index, colIndex)" class="text-rose-600">&times;</button>
                                    </div>
                                </template>
                                <button type="button" @click="addTableColumn(index)" class="rounded-lg border border-slate-300 px-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">+Col</button>
                            </div>

                            <template x-for="(row, rowIndex) in block.rows" :key="rowIndex">
                                <div class="flex gap-2">
                                    <template x-for="(cell, colIndex) in row" :key="colIndex">
                                        <input :name="'content['+index+'][rows]['+rowIndex+']['+colIndex+']'" x-model="block.rows[rowIndex][colIndex]"
                                               class="w-full min-w-[140px] flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                    </template>
                                    <button type="button" @click="removeTableRow(index, rowIndex)" class="text-rose-600">&times;</button>
                                </div>
                            </template>
                            <button type="button" @click="addTableRow(index)" class="text-xs font-semibold text-primary-600 hover:text-primary-700">+ Add Row</button>
                        </div>
                    </template>

                    <template x-if="block.type === 'image'">
                        <div class="space-y-2">
                            <input :name="'content['+index+'][image_url]'" x-model="block.image_url" placeholder="Image URL (https://...)"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <input :name="'content['+index+'][caption]'" x-model="block.caption" placeholder="Caption (optional)"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="text-xs text-slate-400">Leave the URL blank to show the default placeholder graphic.</p>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        {{-- SEO --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">SEO</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Meta Title</label>
                    <input type="text" name="seo[meta_title]" value="{{ old('seo.meta_title', $post->seo?->meta_title) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Focus Keyword</label>
                    <input type="text" name="seo[focus_keyword]" value="{{ old('seo.focus_keyword', $post->seo?->focus_keyword) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Meta Description</label>
                    <textarea name="seo[meta_description]" rows="2"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">{{ old('seo.meta_description', $post->seo?->meta_description) }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Canonical URL</label>
                    <input type="text" name="seo[canonical_url]" value="{{ old('seo.canonical_url', $post->seo?->canonical_url) }}" placeholder="https://mapzoon.com/blog/..."
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                </div>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-5">
                <h3 class="mb-3 text-sm font-semibold text-ink">Open Graph</h3>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">OG Title</label>
                        <input type="text" name="seo[og_title]" value="{{ old('seo.og_title', $post->seo?->og_title) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">OG Description</label>
                        <input type="text" name="seo[og_description]" value="{{ old('seo.og_description', $post->seo?->og_description) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-400">The Featured Image above is used automatically as the og:image.</p>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-5">
                <h3 class="mb-3 text-sm font-semibold text-ink">Twitter Card</h3>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Card Type</label>
                        <select name="seo[twitter_card]" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            @php $twitterCard = old('seo.twitter_card', $post->seo?->twitter_card ?? 'summary_large_image'); @endphp
                            <option value="summary" @selected($twitterCard === 'summary')>Summary</option>
                            <option value="summary_large_image" @selected($twitterCard === 'summary_large_image')>Summary with Large Image</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Twitter Title</label>
                        <input type="text" name="seo[twitter_title]" value="{{ old('seo.twitter_title', $post->seo?->twitter_title) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Twitter Description</label>
                        <input type="text" name="seo[twitter_description]" value="{{ old('seo.twitter_description', $post->seo?->twitter_description) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    </div>
                </div>
            </div>
        </div>

        {{-- JSON-LD Schema --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">JSON-LD Schema</h2>

            <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="hidden" name="seo[enable_article_schema]" value="0">
                    <input type="checkbox" name="seo[enable_article_schema]" value="1" @checked(old('seo.enable_article_schema', $post->seo?->enable_article_schema ?? true))
                           class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    Article Schema
                </label>
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="hidden" name="seo[enable_breadcrumb_schema]" value="0">
                    <input type="checkbox" name="seo[enable_breadcrumb_schema]" value="1" @checked(old('seo.enable_breadcrumb_schema', $post->seo?->enable_breadcrumb_schema ?? true))
                           class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    Breadcrumb Schema
                </label>
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="hidden" name="seo[enable_faq_schema]" value="0">
                    <input type="checkbox" name="seo[enable_faq_schema]" value="1" @checked(old('seo.enable_faq_schema', $post->seo?->enable_faq_schema ?? false))
                           class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    FAQ Schema
                </label>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-5">
                <h3 class="mb-1 text-sm font-semibold text-ink">FAQ Items</h3>
                <p class="mb-3 text-sm text-slate-500">These render as a visible FAQ section on the post and feed the FAQ schema above.</p>

                <template x-for="(faq, index) in faqs" :key="index">
                    <div class="mb-3 rounded-xl border border-slate-200 p-4">
                        <div class="mb-2 flex justify-end">
                            <button type="button" @click="removeFaq(index)" class="text-xs font-medium text-rose-600 hover:text-rose-700">Remove</button>
                        </div>
                        <input :name="'faqs['+index+'][question]'" x-model="faq.question" placeholder="Question"
                               class="mb-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        <textarea :name="'faqs['+index+'][answer]'" x-model="faq.answer" rows="2" placeholder="Answer"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                    </div>
                </template>

                <button type="button" @click="addFaq()" class="text-sm font-semibold text-primary-600 hover:text-primary-700">+ Add FAQ Item</button>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            @if ($post->id)
                <a href="{{ route('admin.blog-posts.preview', $post) }}" target="_blank"
                   class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    Preview
                </a>
            @endif
            <a href="{{ route('admin.blog-posts.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</div>

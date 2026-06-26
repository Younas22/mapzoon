import Quill from 'quill';
import 'quill/dist/quill.snow.css';

function slugify(value) {
    return value
        .toString()
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

export function blogPostManager(config) {
    return {
        config,
        deleteModalOpen: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },

        confirmDelete(id, name) {
            this.deleteTarget = { id, name };
            this.deleteModalOpen = true;
        },

        async deletePost() {
            this.deleting = true;

            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/${this.deleteTarget.id}`, 'DELETE', {});

            this.deleting = false;
            this.deleteModalOpen = false;

            window.AdminUI.toast(data.message, ok ? 'success' : 'error');

            if (ok) {
                document.querySelector('[data-table]')?.dispatchEvent(new CustomEvent('table:refresh'));
            }
        },
    };
}

export function blogPostForm(config) {
    return {
        config,
        quill: null,
        editorFullscreen: false,
        faqs: config.faqs?.length ? config.faqs : [],
        slugTouched: Boolean(config.slugTouched),
        featuredImagePreview: config.featuredImageUrl ?? null,
        status: config.status || 'draft',

        init() {
            this.quill = new Quill(this.$refs.editorContainer, {
                theme: 'snow',
                placeholder: 'Write the article…',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ color: [] }, { background: [] }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        [{ indent: '-1' }, { indent: '+1' }],
                        [{ align: [] }],
                        ['blockquote', 'code-block'],
                        ['link', 'image'],
                        ['clean'],
                    ],
                },
            });

            if (this.config.content) {
                // Setting .innerHTML directly desyncs Quill's internal blot tree from the
                // DOM, which later crashes selection lookups (e.g. on image upload). This
                // routes the initial content through Quill's own clipboard parser instead.
                this.quill.clipboard.dangerouslyPasteHTML(this.config.content);
            }

            this.quill.on('text-change', () => {
                this.$refs.contentInput.value = this.quill.root.innerHTML;
            });

            this.quill.getModule('toolbar').addHandler('image', () => this.uploadContentImage());
        },

        uploadContentImage() {
            // Selection must be captured now, synchronously, while the editor still
            // has focus — by the time the file dialog closes Quill's selection is gone.
            let range;
            try {
                range = this.quill.getSelection(true);
            } catch {
                range = null;
            }
            range ??= { index: this.quill.getLength() };

            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = async () => {
                const file = input.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('image', file);

                const { ok, data } = await window.AdminUI.submitFormData(this.config.imageUploadUrl, 'POST', formData);

                if (ok) {
                    this.quill.insertEmbed(range.index, 'image', data.url, 'user');
                    this.quill.setSelection(range.index + 1, 0, 'user');
                } else {
                    window.AdminUI.toast(data.message ?? 'Image upload failed.', 'error');
                }
            };
            input.click();
        },

        toggleEditorFullscreen() {
            this.editorFullscreen = !this.editorFullscreen;
        },

        syncContent() {
            this.$refs.contentInput.value = this.quill.root.innerHTML;
        },

        onTitleInput(event) {
            if (this.slugTouched) return;

            const slugInput = this.$refs.slugInput;
            if (slugInput) slugInput.value = slugify(event.target.value);
        },

        onSlugInput() {
            this.slugTouched = true;
        },

        onFeaturedImageChange(event) {
            const file = event.target.files[0];
            this.featuredImagePreview = file ? URL.createObjectURL(file) : this.featuredImagePreview;
        },

        addFaq() {
            this.faqs.push({ question: '', answer: '' });
        },

        removeFaq(index) {
            this.faqs.splice(index, 1);
        },
    };
}

function emptyVideoReviewForm() {
    return {
        id: null,
        client_name: '',
        tagline: '',
        company_name: '',
        review_text: '',
        youtube_url: '',
        display_order: 0,
        status: 'active',
        is_visible_on_homepage: true,
    };
}

export default function videoReviewManager(config) {
    return {
        config,
        modalOpen: false,
        deleteModalOpen: false,
        mode: 'create',
        saving: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },
        form: emptyVideoReviewForm(),
        errors: {},
        thumbnailFile: null,
        thumbnailPreview: null,

        openCreate() {
            this.mode = 'create';
            this.form = emptyVideoReviewForm();
            this.errors = {};
            this.thumbnailFile = null;
            this.thumbnailPreview = null;
            this.modalOpen = true;
        },

        async openEdit(id) {
            this.mode = 'edit';
            this.errors = {};
            this.thumbnailFile = null;
            this.modalOpen = true;

            const response = await fetch(`${this.config.baseUrl}/${id}/edit`, {
                headers: { Accept: 'application/json' },
            });
            const data = await response.json();

            this.form = { ...emptyVideoReviewForm(), ...data.videoReview };
            this.thumbnailPreview = data.videoReview.thumbnail_url ?? null;

            for (const key of Object.keys(this.form)) {
                if (this.form[key] === null) {
                    this.form[key] = '';
                }
            }
        },

        closeModal() {
            this.modalOpen = false;
        },

        onThumbnailChange(event) {
            this.thumbnailFile = event.target.files[0] ?? null;
            this.thumbnailPreview = this.thumbnailFile ? URL.createObjectURL(this.thumbnailFile) : this.thumbnailPreview;
        },

        async submit() {
            this.saving = true;
            this.errors = {};

            const isEdit = this.mode === 'edit';
            const url = isEdit ? `${this.config.baseUrl}/${this.form.id}` : this.config.storeUrl;

            const formData = new FormData();
            Object.entries(this.form).forEach(([key, value]) => {
                if (key === 'id' || key === 'thumbnail_url' || value === null) return;
                if (key === 'is_visible_on_homepage') {
                    formData.append(key, value ? '1' : '0');
                    return;
                }
                formData.append(key, value);
            });
            if (this.thumbnailFile) formData.append('thumbnail', this.thumbnailFile);
            if (isEdit) formData.append('_method', 'PUT');

            const { ok, status, data } = await window.AdminUI.submitFormData(url, 'POST', formData);

            this.saving = false;

            if (! ok) {
                if (status === 422) {
                    this.errors = data.errors ?? {};
                } else {
                    window.AdminUI.toast(data.message ?? 'Something went wrong.', 'error');
                }
                return;
            }

            this.modalOpen = false;
            window.AdminUI.toast(data.message, 'success');
            document.querySelector('[data-table]')?.dispatchEvent(new CustomEvent('table:refresh'));
        },

        confirmDelete(id, name) {
            this.deleteTarget = { id, name };
            this.deleteModalOpen = true;
        },

        async deleteVideoReview() {
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

function slugify(value) {
    // Keeps any Unicode letter/number (Urdu, Arabic, etc.), not just a-z0-9 —
    // matches the backend's alpha_dash rule, which is Unicode-aware. An
    // ASCII-only regex here would strip non-Latin titles down to an empty
    // string and fail the "slug is required" validation on submit.
    return value
        .toString()
        .toLowerCase()
        .trim()
        .replace(/[^\p{L}\p{N}\s-]+/gu, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

export function caseStudyManager(config) {
    return {
        config,
        deleteModalOpen: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },

        confirmDelete(id, name) {
            this.deleteTarget = { id, name };
            this.deleteModalOpen = true;
        },

        async deleteCaseStudy() {
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

export function caseStudyForm(config) {
    return {
        config,
        slugTouched: Boolean(config.slugTouched),
        imagePreview: config.imagePreview ?? null,
        existingScreenshots: config.existingScreenshots?.length ? config.existingScreenshots : [],
        newScreenshotPreviews: [],
        ownerPhotoPreview: config.ownerPhotoPreview ?? null,
        clients: config.clients ?? [],

        onTitleInput(event) {
            if (this.slugTouched) return;

            const slugInput = this.$refs.slugInput;
            if (slugInput) slugInput.value = slugify(event.target.value);
        },

        onSlugInput() {
            this.slugTouched = true;
        },

        onImageChange(event) {
            const file = event.target.files[0];
            this.imagePreview = file ? URL.createObjectURL(file) : this.imagePreview;
        },

        onScreenshotsChange(event) {
            this.newScreenshotPreviews = Array.from(event.target.files).map((file) => URL.createObjectURL(file));
        },

        removeExistingScreenshot(index) {
            this.existingScreenshots.splice(index, 1);
        },

        onOwnerPhotoChange(event) {
            const file = event.target.files[0];
            this.ownerPhotoPreview = file ? URL.createObjectURL(file) : this.ownerPhotoPreview;
        },

        // Autofills the reviewer name and photo preview from the linked
        // client so admins don't have to retype/re-upload what's already
        // on file — the name stays editable afterwards.
        onClientSelect(clientId) {
            const client = this.clients.find((c) => String(c.id) === String(clientId));
            if (!client) return;

            const nameInput = this.$refs.ownerNameInput;
            if (nameInput) nameInput.value = client.owner_name ?? '';

            if (client.photo_url) this.ownerPhotoPreview = client.photo_url;
        },
    };
}

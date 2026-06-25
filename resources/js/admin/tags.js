export default function tagManager(config) {
    return {
        config,
        modalOpen: false,
        deleteModalOpen: false,
        mode: 'create',
        editingId: null,
        saving: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },
        form: { name: '' },
        errors: {},

        openCreate() {
            this.mode = 'create';
            this.editingId = null;
            this.form = { name: '' };
            this.errors = {};
            this.modalOpen = true;
        },

        async openEdit(id) {
            this.mode = 'edit';
            this.editingId = id;
            this.errors = {};
            this.modalOpen = true;

            const response = await fetch(`${this.config.baseUrl}/${id}/edit`, {
                headers: { Accept: 'application/json' },
            });
            const data = await response.json();

            this.form = { name: data.tag.name };
        },

        closeModal() {
            this.modalOpen = false;
        },

        async submit() {
            this.saving = true;
            this.errors = {};

            const url = this.mode === 'create' ? this.config.storeUrl : `${this.config.baseUrl}/${this.editingId}`;
            const method = this.mode === 'create' ? 'POST' : 'PUT';

            const { ok, status, data } = await window.AdminUI.submitJson(url, method, this.form);

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

        async deleteTag() {
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

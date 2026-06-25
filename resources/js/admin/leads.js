export function leadManager(config) {
    return {
        config,
        modalOpen: false,
        deleteModalOpen: false,
        mode: 'create',
        editingId: null,
        saving: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },
        form: emptyLeadForm(),
        errors: {},

        openCreate() {
            this.mode = 'create';
            this.editingId = null;
            this.form = emptyLeadForm();
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

            this.form = { ...emptyLeadForm(), ...data.lead };

            // Normalize nullable fields to '' so they bind cleanly to <select>/<input>.
            for (const key of Object.keys(this.form)) {
                if (this.form[key] === null) {
                    this.form[key] = '';
                }
            }
        },

        closeModal() {
            this.modalOpen = false;
        },

        async submit() {
            this.saving = true;
            this.errors = {};

            const url = this.mode === 'create' ? this.config.storeUrl : `${this.config.baseUrl}/${this.editingId}`;
            const method = this.mode === 'create' ? 'POST' : 'PUT';

            const payload = {
                ...this.form,
                assigned_to: this.form.assigned_to || null,
                follow_up_date: this.form.follow_up_date || null,
            };

            const { ok, status, data } = await window.AdminUI.submitJson(url, method, payload);

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

        async changeStatus(id, status) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/${id}/status`, 'PATCH', { status });

            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            document.querySelector('[data-table]')?.dispatchEvent(new CustomEvent('table:refresh'));
        },

        confirmDelete(id, name) {
            this.deleteTarget = { id, name };
            this.deleteModalOpen = true;
        },

        async deleteLead() {
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

export function leadShow(config) {
    return {
        config,
        deleteModalOpen: false,
        deleting: false,
        savingNote: false,
        noteText: '',

        async changeStatus(status) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/status`, 'PATCH', { status });
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
        },

        async changeAssignee(assignedTo) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/assign`, 'PATCH', { assigned_to: assignedTo || null });
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
        },

        async addNote() {
            if (! this.noteText.trim()) return;

            this.savingNote = true;

            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/notes`, 'POST', { note: this.noteText });

            this.savingNote = false;

            if (! ok) {
                window.AdminUI.toast(data.message ?? 'Could not add note.', 'error');
                return;
            }

            document.getElementById('lead-notes-empty')?.remove();
            document.getElementById('lead-notes-list')?.insertAdjacentHTML('afterbegin', data.html);
            this.noteText = '';
            window.AdminUI.toast(data.message, 'success');
        },

        confirmDelete() {
            this.deleteModalOpen = true;
        },

        async deleteLead() {
            this.deleting = true;

            const { ok, data } = await window.AdminUI.submitJson(this.config.baseUrl, 'DELETE', {});

            this.deleting = false;
            this.deleteModalOpen = false;

            window.AdminUI.toast(data.message, ok ? 'success' : 'error');

            if (ok) {
                window.location.href = this.config.indexUrl;
            }
        },
    };
}

function emptyLeadForm() {
    return {
        name: '',
        phone: '',
        email: '',
        business_name: '',
        service: '',
        message: '',
        status: 'new',
        source: 'manual',
        follow_up_date: '',
        assigned_to: '',
    };
}

export function jobApplicationManager(config) {
    return {
        config,
        deleteModalOpen: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },

        async changeStatus(id, status) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/${id}/status`, 'PATCH', { status });

            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            document.querySelector('[data-table]')?.dispatchEvent(new CustomEvent('table:refresh'));
        },

        confirmDelete(id, name) {
            this.deleteTarget = { id, name };
            this.deleteModalOpen = true;
        },

        async deleteApplication() {
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

export function jobApplicationShow(config) {
    return {
        config,
        deleteModalOpen: false,
        deleting: false,

        async changeStatus(status) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/status`, 'PATCH', { status });
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
        },

        confirmDelete() {
            this.deleteModalOpen = true;
        },

        async deleteApplication() {
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

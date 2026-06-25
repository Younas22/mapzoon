import { isGroupFullyChecked, toggleGroupPermissions } from './permission-groups';

export function userManager(config = {}) {
    return {
        config,
        deleteModalOpen: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },

        confirmDelete(id, name) {
            this.deleteTarget = { id, name };
            this.deleteModalOpen = true;
        },

        async deleteUser() {
            this.deleting = true;

            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/${this.deleteTarget.id}`, 'DELETE', {});

            this.deleting = false;
            this.deleteModalOpen = false;

            window.AdminUI.toast(data.message, ok ? 'success' : 'error');

            if (ok) {
                const table = document.querySelector('[data-table]');

                if (table) {
                    table.dispatchEvent(new CustomEvent('table:refresh'));
                } else if (this.config.indexUrl) {
                    window.location.href = this.config.indexUrl;
                }
            }
        },
    };
}

export function userForm(config) {
    return {
        avatarPreview: config.avatarUrl ?? null,
        form: {
            permissions: config.permissionIds ?? [],
        },

        onAvatarChange(event) {
            const file = event.target.files[0];
            this.avatarPreview = file ? URL.createObjectURL(file) : this.avatarPreview;
        },

        isGroupFullyChecked(ids) {
            return isGroupFullyChecked(this.form.permissions, ids);
        },

        toggleGroup(ids, checked) {
            this.form.permissions = toggleGroupPermissions(this.form.permissions, ids, checked);
        },
    };
}

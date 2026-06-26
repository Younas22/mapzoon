function emptyTaskForm() {
    return {
        title: '',
        description: '',
        priority: 'medium',
        status: 'pending',
        start_date: '',
        due_date: '',
        assigned_to: '',
        project_id: '',
    };
}

export function taskManager(config) {
    return {
        config,
        modalOpen: false,
        deleteModalOpen: false,
        mode: 'create',
        editingId: null,
        saving: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },
        form: emptyTaskForm(),
        errors: {},

        openCreate() {
            this.mode = 'create';
            this.editingId = null;
            this.form = emptyTaskForm();
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

            this.form = { ...emptyTaskForm(), ...data.task };

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

            const payload = { ...this.form, assigned_to: this.form.assigned_to || null, project_id: this.form.project_id || null };

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

        confirmDelete(id, name) {
            this.deleteTarget = { id, name };
            this.deleteModalOpen = true;
        },

        async deleteTask() {
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

export function taskShow(config) {
    return {
        config,
        deleteModalOpen: false,
        deleting: false,
        noteText: '',
        savingNote: false,
        commentText: '',
        savingComment: false,
        subtaskTitle: '',
        savingSubtask: false,
        uploadingAttachment: false,

        async changeStatus(status) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/status`, 'PATCH', { status });
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            if (ok) setTimeout(() => window.location.reload(), 600);
        },

        async changeProgress(progress) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/progress`, 'PATCH', { progress });
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
        },

        async changeAssignee(assignedTo) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/assign`, 'PATCH', { assigned_to: assignedTo || null });
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            if (ok) setTimeout(() => window.location.reload(), 600);
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

            document.getElementById('task-notes-empty')?.remove();
            document.getElementById('task-notes-list')?.insertAdjacentHTML('afterbegin', data.html);
            this.noteText = '';
            window.AdminUI.toast(data.message, 'success');
        },

        async addComment() {
            if (! this.commentText.trim()) return;
            this.savingComment = true;

            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/comments`, 'POST', { comment: this.commentText });

            this.savingComment = false;

            if (! ok) {
                window.AdminUI.toast(data.message ?? 'Could not add comment.', 'error');
                return;
            }

            document.getElementById('task-comments-empty')?.remove();
            document.getElementById('task-comments-list')?.insertAdjacentHTML('afterbegin', data.html);
            this.commentText = '';
            window.AdminUI.toast(data.message, 'success');
        },

        async addSubtask() {
            if (! this.subtaskTitle.trim()) return;
            this.savingSubtask = true;

            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/subtasks`, 'POST', { title: this.subtaskTitle });

            this.savingSubtask = false;

            if (! ok) {
                window.AdminUI.toast(data.message ?? 'Could not add subtask.', 'error');
                return;
            }

            document.getElementById('task-subtasks-empty')?.remove();
            document.getElementById('task-subtasks-list')?.insertAdjacentHTML('beforeend', data.html);
            this.subtaskTitle = '';
        },

        async toggleSubtask(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/subtasks/${id}`, 'PATCH', {});

            if (! ok) {
                event.target.checked = ! event.target.checked;
                window.AdminUI.toast(data.message ?? 'Could not update subtask.', 'error');
                return;
            }

            const title = event.target.closest('[data-subtask-item]')?.querySelector('[data-subtask-title]');
            title?.classList.toggle('line-through', data.is_completed);
            title?.classList.toggle('text-slate-400', data.is_completed);
        },

        async removeSubtask(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/subtasks/${id}`, 'DELETE', {});

            window.AdminUI.toast(data.message, ok ? 'success' : 'error');

            if (ok) {
                event.target.closest('[data-subtask-item]')?.remove();
            }
        },

        async uploadAttachment(event) {
            const file = event.target.files[0];
            if (! file) return;

            this.uploadingAttachment = true;

            const formData = new FormData();
            formData.append('file', file);

            const { ok, data } = await window.AdminUI.submitFormData(`${this.config.baseUrl}/attachments`, 'POST', formData);

            this.uploadingAttachment = false;
            event.target.value = '';

            if (! ok) {
                window.AdminUI.toast(data.message ?? 'Could not upload file.', 'error');
                return;
            }

            document.getElementById('task-attachments-empty')?.remove();
            document.getElementById('task-attachments-list')?.insertAdjacentHTML('afterbegin', data.html);
            window.AdminUI.toast(data.message, 'success');
        },

        async removeAttachment(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/attachments/${id}`, 'DELETE', {});

            window.AdminUI.toast(data.message, ok ? 'success' : 'error');

            if (ok) {
                event.target.closest('[data-attachment-item]')?.remove();
            }
        },

        confirmDelete() {
            this.deleteModalOpen = true;
        },

        async deleteTask() {
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

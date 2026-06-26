function emptyProjectForm() {
    return {
        name: '',
        project_type: '',
        description: '',
        client_id: '',
        budget: '',
        start_date: '',
        end_date: '',
        status: 'planning',
        priority: 'medium',
        services_included: [],
    };
}

export function projectManager(config) {
    return {
        config,
        modalOpen: false,
        deleteModalOpen: false,
        mode: 'create',
        editingId: null,
        saving: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },
        form: emptyProjectForm(),
        errors: {},

        openCreate() {
            this.mode = 'create';
            this.editingId = null;
            this.form = emptyProjectForm();
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

            this.form = { ...emptyProjectForm(), ...data.project };

            for (const key of Object.keys(this.form)) {
                if (this.form[key] === null) {
                    this.form[key] = key === 'services_included' ? [] : '';
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

        async deleteProject() {
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

function emptyCredentialForm() {
    return {
        id: null,
        platform: 'custom',
        label: '',
        username: '',
        password: '',
        recovery_email: '',
        recovery_phone: '',
        url: '',
        notes: '',
    };
}

function emptyMilestoneForm() {
    return { title: '', date: '' };
}

export function projectShow(config) {
    return {
        config,
        activeTab: 'overview',
        deleteModalOpen: false,
        deleting: false,

        savingProgress: false,

        teamSelected: config.teamMemberIds ?? [],
        savingTeam: false,

        noteText: '',
        savingNote: false,

        messageText: '',
        savingMessage: false,

        uploadingFile: false,

        credentialModalOpen: false,
        savingCredential: false,
        credentialForm: emptyCredentialForm(),
        credentialErrors: {},
        revealedCredentials: {},
        revealingCredentialId: null,

        historyModalOpen: false,
        loadingHistory: false,
        historyCredentialId: null,
        historyEntries: [],
        revealedHistory: {},
        revealingHistoryId: null,

        milestoneForm: emptyMilestoneForm(),
        savingMilestone: false,

        async changeProgress(progress) {
            this.savingProgress = true;
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/progress`, 'PATCH', { progress });
            this.savingProgress = false;
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
        },

        async saveTeam() {
            this.savingTeam = true;
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/team`, 'PUT', { user_ids: this.teamSelected });
            this.savingTeam = false;
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

            document.getElementById('project-notes-empty')?.remove();
            document.getElementById('project-notes-list')?.insertAdjacentHTML('afterbegin', data.html);
            this.noteText = '';
            window.AdminUI.toast(data.message, 'success');
        },

        async addDiscussion() {
            if (! this.messageText.trim()) return;
            this.savingMessage = true;

            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/discussions`, 'POST', { message: this.messageText });

            this.savingMessage = false;

            if (! ok) {
                window.AdminUI.toast(data.message ?? 'Could not post message.', 'error');
                return;
            }

            document.getElementById('project-discussions-empty')?.remove();
            document.getElementById('project-discussions-list')?.insertAdjacentHTML('afterbegin', data.html);
            this.messageText = '';
            window.AdminUI.toast(data.message, 'success');
        },

        async uploadFile(event) {
            const file = event.target.files[0];
            if (! file) return;

            this.uploadingFile = true;

            const formData = new FormData();
            formData.append('file', file);

            const { ok, data } = await window.AdminUI.submitFormData(`${this.config.baseUrl}/files`, 'POST', formData);

            this.uploadingFile = false;
            event.target.value = '';

            if (! ok) {
                window.AdminUI.toast(data.message ?? 'Could not upload file.', 'error');
                return;
            }

            document.getElementById('project-files-empty')?.remove();
            document.getElementById('project-files-list')?.insertAdjacentHTML('afterbegin', data.html);
            window.AdminUI.toast(data.message, 'success');
        },

        async removeFile(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/files/${id}`, 'DELETE', {});
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            if (ok) event.target.closest('[data-file-item]')?.remove();
        },

        openCreateCredential() {
            this.credentialForm = emptyCredentialForm();
            this.credentialErrors = {};
            this.credentialModalOpen = true;
        },

        openEditCredential(credential) {
            // The server never sends the password down for edit — it's fetched
            // on demand (and logged) only if the user clicks "Reveal" below.
            this.credentialForm = { ...credential, password: '' };
            this.credentialErrors = {};
            this.credentialModalOpen = true;
        },

        async revealPasswordIntoForm() {
            if (! this.credentialForm.id) return;

            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/credentials/${this.credentialForm.id}/reveal`, 'POST', {});

            if (! ok) {
                window.AdminUI.toast(data.message ?? 'Could not reveal password.', 'error');
                return;
            }

            this.credentialForm.password = data.password ?? '';
        },

        async submitCredential() {
            this.savingCredential = true;
            this.credentialErrors = {};

            const isEdit = !! this.credentialForm.id;
            const url = isEdit ? `${this.config.baseUrl}/credentials/${this.credentialForm.id}` : `${this.config.baseUrl}/credentials`;
            const method = isEdit ? 'PUT' : 'POST';

            const { ok, status, data } = await window.AdminUI.submitJson(url, method, this.credentialForm);

            this.savingCredential = false;

            if (! ok) {
                if (status === 422) {
                    this.credentialErrors = data.errors ?? {};
                } else {
                    window.AdminUI.toast(data.message ?? 'Something went wrong.', 'error');
                }
                return;
            }

            this.credentialModalOpen = false;
            window.AdminUI.toast(data.message, 'success');

            const existing = isEdit ? document.getElementById(`credential-${this.credentialForm.id}`) : null;
            if (existing) {
                existing.outerHTML = data.html;
            } else {
                document.getElementById('project-credentials-empty')?.remove();
                document.getElementById('project-credentials-list')?.insertAdjacentHTML('beforeend', data.html);
            }
        },

        async removeCredential(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/credentials/${id}`, 'DELETE', {});
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            if (ok) event.target.closest('[data-credential-item]')?.remove();
        },

        /**
         * Every reveal re-fetches from the server (never cached client-side)
         * so each view of a password is individually audit-logged. Hiding is
         * purely local — it does not need another round trip.
         */
        async toggleReveal(id) {
            if (this.isRevealed(id)) {
                const { [id]: _omit, ...rest } = this.revealedCredentials;
                this.revealedCredentials = rest;
                return;
            }

            this.revealingCredentialId = id;
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/credentials/${id}/reveal`, 'POST', {});
            this.revealingCredentialId = null;

            if (! ok) {
                window.AdminUI.toast(data.message ?? 'Could not reveal password.', 'error');
                return;
            }

            this.revealedCredentials = { ...this.revealedCredentials, [id]: data.password };
        },

        isRevealed(id) {
            return Object.prototype.hasOwnProperty.call(this.revealedCredentials, id);
        },

        revealedPassword(id) {
            return this.revealedCredentials[id] ?? '';
        },

        async openHistory(credentialId) {
            this.historyCredentialId = credentialId;
            this.historyEntries = [];
            this.revealedHistory = {};
            this.loadingHistory = true;
            this.historyModalOpen = true;

            const response = await fetch(`${this.config.baseUrl}/credentials/${credentialId}/history`, {
                headers: { Accept: 'application/json' },
            });
            const data = await response.json();

            this.loadingHistory = false;
            this.historyEntries = data.history ?? [];
        },

        async toggleHistoryReveal(historyId) {
            if (this.isHistoryRevealed(historyId)) {
                const { [historyId]: _omit, ...rest } = this.revealedHistory;
                this.revealedHistory = rest;
                return;
            }

            this.revealingHistoryId = historyId;
            const { ok, data } = await window.AdminUI.submitJson(
                `${this.config.baseUrl}/credentials/${this.historyCredentialId}/history/${historyId}/reveal`,
                'POST',
                {}
            );
            this.revealingHistoryId = null;

            if (! ok) {
                window.AdminUI.toast(data.message ?? 'Could not reveal password.', 'error');
                return;
            }

            this.revealedHistory = { ...this.revealedHistory, [historyId]: data.password };
        },

        isHistoryRevealed(historyId) {
            return Object.prototype.hasOwnProperty.call(this.revealedHistory, historyId);
        },

        revealedHistoryPassword(historyId) {
            return this.revealedHistory[historyId] ?? '';
        },

        async addMilestone() {
            if (! this.milestoneForm.title.trim()) return;
            this.savingMilestone = true;

            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/milestones`, 'POST', this.milestoneForm);

            this.savingMilestone = false;

            if (! ok) {
                window.AdminUI.toast(data.message ?? 'Could not add milestone.', 'error');
                return;
            }

            document.getElementById('project-milestones-empty')?.remove();
            document.getElementById('project-milestones-list')?.insertAdjacentHTML('beforeend', data.html);
            this.milestoneForm = emptyMilestoneForm();
        },

        async toggleMilestone(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/milestones/${id}`, 'PATCH', {});

            if (! ok) {
                event.target.checked = ! event.target.checked;
                window.AdminUI.toast(data.message ?? 'Could not update milestone.', 'error');
                return;
            }

            const title = event.target.closest('[data-milestone-item]')?.querySelector('[data-milestone-title]');
            title?.classList.toggle('line-through', data.is_completed);
            title?.classList.toggle('text-slate-400', data.is_completed);
        },

        async removeMilestone(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/milestones/${id}`, 'DELETE', {});
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            if (ok) event.target.closest('[data-milestone-item]')?.remove();
        },

        confirmDelete() {
            this.deleteModalOpen = true;
        },

        async deleteProject() {
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

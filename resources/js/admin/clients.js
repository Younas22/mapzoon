function emptyClientForm() {
    return {
        company_name: '',
        owner_name: '',
        phone: '',
        email: '',
        website: '',
        address: '',
        industry: '',
        notes: '',
        status: 'active',
        client_type: 'business',
    };
}

export function clientManager(config) {
    return {
        config,
        modalOpen: false,
        deleteModalOpen: false,
        mode: 'create',
        editingId: null,
        saving: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },
        form: emptyClientForm(),
        errors: {},

        openCreate() {
            this.mode = 'create';
            this.editingId = null;
            this.form = emptyClientForm();
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

            this.form = { ...emptyClientForm(), ...data.client };

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

        async deleteClient() {
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

function emptyContactForm() {
    return { id: null, name: '', designation: '', phone: '', email: '', is_primary: false };
}

function emptyContractForm() {
    return { id: null, title: '', value: '', start_date: '', end_date: '', status: 'active', notes: '' };
}

function emptyInvoiceForm() {
    return { id: null, invoice_number: '', amount: '', status: 'unpaid', issue_date: '', due_date: '', paid_at: '', notes: '' };
}

export function clientShow(config) {
    return {
        config,
        activeTab: 'overview',
        deleteModalOpen: false,
        deleting: false,

        // Overview
        overviewForm: { ...config.client },
        overviewErrors: {},
        savingOverview: false,

        async saveOverview() {
            this.savingOverview = true;
            this.overviewErrors = {};

            const { ok, status, data } = await window.AdminUI.submitJson(this.config.baseUrl, 'PUT', this.overviewForm);

            this.savingOverview = false;

            if (! ok) {
                if (status === 422) {
                    this.overviewErrors = data.errors ?? {};
                } else {
                    window.AdminUI.toast(data.message ?? 'Something went wrong.', 'error');
                }
                return;
            }

            window.AdminUI.toast(data.message, 'success');
        },

        // Contacts
        contactModalOpen: false,
        savingContact: false,
        contactForm: emptyContactForm(),
        contactErrors: {},

        // Contracts
        contractModalOpen: false,
        savingContract: false,
        contractForm: emptyContractForm(),
        contractFile: null,
        contractErrors: {},

        // Invoices
        invoiceModalOpen: false,
        savingInvoice: false,
        invoiceForm: emptyInvoiceForm(),
        invoiceFile: null,
        invoiceErrors: {},

        // Files / team
        uploadingFile: false,
        savingTeam: false,
        teamSelected: config.teamMemberIds ?? [],

        openCreateContact() {
            this.contactForm = emptyContactForm();
            this.contactErrors = {};
            this.contactModalOpen = true;
        },

        openEditContact(contact) {
            this.contactForm = { ...contact };
            this.contactErrors = {};
            this.contactModalOpen = true;
        },

        async submitContact() {
            this.savingContact = true;
            this.contactErrors = {};

            const isEdit = !! this.contactForm.id;
            const url = isEdit ? `${this.config.baseUrl}/contacts/${this.contactForm.id}` : `${this.config.baseUrl}/contacts`;
            const method = isEdit ? 'PUT' : 'POST';

            const { ok, status, data } = await window.AdminUI.submitJson(url, method, this.contactForm);

            this.savingContact = false;

            if (! ok) {
                if (status === 422) {
                    this.contactErrors = data.errors ?? {};
                } else {
                    window.AdminUI.toast(data.message ?? 'Something went wrong.', 'error');
                }
                return;
            }

            this.contactModalOpen = false;
            window.AdminUI.toast(data.message, 'success');

            const existing = isEdit ? document.getElementById(`contact-${this.contactForm.id}`) : null;
            if (existing) {
                existing.outerHTML = data.html;
            } else {
                document.getElementById('client-contacts-empty')?.remove();
                document.getElementById('client-contacts-list')?.insertAdjacentHTML('beforeend', data.html);
            }
        },

        async removeContact(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/contacts/${id}`, 'DELETE', {});
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            if (ok) event.target.closest('[data-contact-item]')?.remove();
        },

        openCreateContract() {
            this.contractForm = emptyContractForm();
            this.contractFile = null;
            this.contractErrors = {};
            this.contractModalOpen = true;
        },

        openEditContract(contract) {
            this.contractForm = { ...contract };
            this.contractFile = null;
            this.contractErrors = {};
            this.contractModalOpen = true;
        },

        onContractFileChange(event) {
            this.contractFile = event.target.files[0] ?? null;
        },

        async submitContract() {
            this.savingContract = true;
            this.contractErrors = {};

            const isEdit = !! this.contractForm.id;
            const url = isEdit ? `${this.config.baseUrl}/contracts/${this.contractForm.id}` : `${this.config.baseUrl}/contracts`;

            const formData = new FormData();
            Object.entries(this.contractForm).forEach(([key, value]) => {
                if (key !== 'id' && value !== null) formData.append(key, value);
            });
            if (this.contractFile) formData.append('file', this.contractFile);
            if (isEdit) formData.append('_method', 'PUT');

            const { ok, status, data } = await window.AdminUI.submitFormData(url, 'POST', formData);

            this.savingContract = false;

            if (! ok) {
                if (status === 422) {
                    this.contractErrors = data.errors ?? {};
                } else {
                    window.AdminUI.toast(data.message ?? 'Something went wrong.', 'error');
                }
                return;
            }

            this.contractModalOpen = false;
            window.AdminUI.toast(data.message, 'success');

            const existing = isEdit ? document.getElementById(`contract-${this.contractForm.id}`) : null;
            if (existing) {
                existing.outerHTML = data.html;
            } else {
                document.getElementById('client-contracts-empty')?.remove();
                document.getElementById('client-contracts-list')?.insertAdjacentHTML('beforeend', data.html);
            }
        },

        async removeContract(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/contracts/${id}`, 'DELETE', {});
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            if (ok) event.target.closest('[data-contract-item]')?.remove();
        },

        openCreateInvoice() {
            this.invoiceForm = emptyInvoiceForm();
            this.invoiceFile = null;
            this.invoiceErrors = {};
            this.invoiceModalOpen = true;
        },

        openEditInvoice(invoice) {
            this.invoiceForm = { ...invoice };
            this.invoiceFile = null;
            this.invoiceErrors = {};
            this.invoiceModalOpen = true;
        },

        onInvoiceFileChange(event) {
            this.invoiceFile = event.target.files[0] ?? null;
        },

        async submitInvoice() {
            this.savingInvoice = true;
            this.invoiceErrors = {};

            const isEdit = !! this.invoiceForm.id;
            const url = isEdit ? `${this.config.baseUrl}/invoices/${this.invoiceForm.id}` : `${this.config.baseUrl}/invoices`;

            const formData = new FormData();
            Object.entries(this.invoiceForm).forEach(([key, value]) => {
                if (key !== 'id' && value !== null) formData.append(key, value);
            });
            if (this.invoiceFile) formData.append('file', this.invoiceFile);
            if (isEdit) formData.append('_method', 'PUT');

            const { ok, status, data } = await window.AdminUI.submitFormData(url, 'POST', formData);

            this.savingInvoice = false;

            if (! ok) {
                if (status === 422) {
                    this.invoiceErrors = data.errors ?? {};
                } else {
                    window.AdminUI.toast(data.message ?? 'Something went wrong.', 'error');
                }
                return;
            }

            this.invoiceModalOpen = false;
            window.AdminUI.toast(data.message, 'success');

            const existing = isEdit ? document.getElementById(`invoice-${this.invoiceForm.id}`) : null;
            if (existing) {
                existing.outerHTML = data.html;
            } else {
                document.getElementById('client-invoices-empty')?.remove();
                document.getElementById('client-invoices-list')?.insertAdjacentHTML('beforeend', data.html);
            }
        },

        async removeInvoice(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/invoices/${id}`, 'DELETE', {});
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            if (ok) event.target.closest('[data-invoice-item]')?.remove();
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

            document.getElementById('client-files-empty')?.remove();
            document.getElementById('client-files-list')?.insertAdjacentHTML('afterbegin', data.html);
            window.AdminUI.toast(data.message, 'success');
        },

        async removeFile(id, event) {
            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/files/${id}`, 'DELETE', {});
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
            if (ok) event.target.closest('[data-file-item]')?.remove();
        },

        async saveTeam() {
            this.savingTeam = true;

            const { ok, data } = await window.AdminUI.submitJson(`${this.config.baseUrl}/team`, 'PUT', { user_ids: this.teamSelected });

            this.savingTeam = false;
            window.AdminUI.toast(data.message, ok ? 'success' : 'error');
        },

        confirmDelete() {
            this.deleteModalOpen = true;
        },

        async deleteClient() {
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

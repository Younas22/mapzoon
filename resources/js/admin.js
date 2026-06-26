import './bootstrap';
import Alpine from 'alpinejs';
import roleManager from './admin/roles';
import { userManager, userForm } from './admin/users';
import { leadManager, leadShow } from './admin/leads';
import categoryManager from './admin/categories';
import tagManager from './admin/tags';
import { blogPostManager, blogPostForm } from './admin/blog-posts';
import { taskManager, taskShow } from './admin/tasks';
import { clientManager, clientShow } from './admin/clients';
import { projectManager, projectShow } from './admin/projects';
import teamMemberManager from './admin/team-members';
import videoReviewManager from './admin/video-reviews';

window.Alpine = Alpine;
Alpine.data('roleManager', roleManager);
Alpine.data('userManager', userManager);
Alpine.data('userForm', userForm);
Alpine.data('leadManager', leadManager);
Alpine.data('leadShow', leadShow);
Alpine.data('categoryManager', categoryManager);
Alpine.data('tagManager', tagManager);
Alpine.data('blogPostManager', blogPostManager);
Alpine.data('blogPostForm', blogPostForm);
Alpine.data('taskManager', taskManager);
Alpine.data('taskShow', taskShow);
Alpine.data('clientManager', clientManager);
Alpine.data('clientShow', clientShow);
Alpine.data('projectManager', projectManager);
Alpine.data('projectShow', projectShow);
Alpine.data('teamMemberManager', teamMemberManager);
Alpine.data('videoReviewManager', videoReviewManager);

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

/**
 * Generic AJAX-driven table: search, sort, and paginate against a JSON
 * endpoint that returns { html: '<rendered rows + pagination partial>' }.
 * Any element can trigger a refresh by dispatching a "table:refresh"
 * CustomEvent on the [data-table] container.
 */
class AdminDataTable {
    constructor(root) {
        this.root = root;
        this.url = root.dataset.tableUrl;
        this.body = root.querySelector('[data-table-body]');
        this.searchInput = root.querySelector('[data-table-search]');
        this.filterInputs = root.querySelectorAll('[data-table-filter]');
        this.params = new URLSearchParams();
        this.searchTimer = null;

        this.body.addEventListener('click', (event) => this.onBodyClick(event));
        this.searchInput?.addEventListener('input', () => this.onSearchInput());
        this.filterInputs.forEach((input) => input.addEventListener('change', () => this.onFilterChange(input)));
        root.addEventListener('table:refresh', () => this.fetchPage());
    }

    onFilterChange(input) {
        if (input.value) {
            this.params.set(input.dataset.tableFilter, input.value);
        } else {
            this.params.delete(input.dataset.tableFilter);
        }

        this.params.delete('page');
        this.fetchPage();
    }

    onSearchInput() {
        clearTimeout(this.searchTimer);
        this.searchTimer = setTimeout(() => {
            this.params.set('q', this.searchInput.value);
            this.params.delete('page');
            this.fetchPage();
        }, 350);
    }

    onBodyClick(event) {
        const sortEl = event.target.closest('[data-sort]');
        if (sortEl) {
            const field = sortEl.dataset.sort;
            const currentDir = this.params.get('sort') === field && this.params.get('dir') === 'asc' ? 'desc' : 'asc';
            this.params.set('sort', field);
            this.params.set('dir', currentDir);
            this.fetchPage();
            return;
        }

        const pageEl = event.target.closest('[data-page]');
        if (pageEl) {
            event.preventDefault();
            this.params.set('page', pageEl.dataset.page);
            this.fetchPage();
        }
    }

    async fetchPage() {
        this.body.classList.add('opacity-50');

        const response = await fetch(`${this.url}?${this.params.toString()}`, {
            headers: { Accept: 'application/json' },
        });

        const data = await response.json();
        this.body.innerHTML = data.html;
        this.body.classList.remove('opacity-50');
    }
}

function initDataTables() {
    document.querySelectorAll('[data-table]').forEach((root) => new AdminDataTable(root));
}

/**
 * Posts a JSON form payload and returns { ok, data }. Validation errors
 * (422) are returned with ok === false so callers can render them inline.
 */
async function submitJson(url, method, payload) {
    const response = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
        },
        body: JSON.stringify(payload),
    });

    const data = await response.json();

    return { ok: response.ok, status: response.status, data };
}

/**
 * Posts a multipart FormData payload (file uploads) and returns { ok, data }.
 */
async function submitFormData(url, method, formData) {
    const response = await fetch(url, {
        method,
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
        },
        body: formData,
    });

    const data = await response.json();

    return { ok: response.ok, status: response.status, data };
}

function toast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const colors = type === 'success'
        ? 'bg-primary-600'
        : 'bg-rose-600';

    const el = document.createElement('div');
    el.className = `${colors} text-white text-sm font-medium px-4 py-3 rounded-lg shadow-lg transition-all duration-300 translate-x-4 opacity-0`;
    el.textContent = message;
    container.appendChild(el);

    requestAnimationFrame(() => el.classList.remove('translate-x-4', 'opacity-0'));

    setTimeout(() => {
        el.classList.add('opacity-0');
        setTimeout(() => el.remove(), 300);
    }, 3500);
}

window.AdminUI = { submitJson, submitFormData, csrfToken, toast };

document.addEventListener('DOMContentLoaded', () => {
    initDataTables();
    Alpine.start();
});

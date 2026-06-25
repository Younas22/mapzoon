function emptyBlock(type) {
    const base = { type, text: '', cite: '', items: [], headers: [], rows: [], image_url: '', caption: '' };

    if (type === 'list') {
        base.items = [''];
    }

    if (type === 'table') {
        base.headers = ['Column 1', 'Column 2'];
        base.rows = [['', '']];
    }

    return base;
}

function slugify(value) {
    return value
        .toString()
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

export function blogPostManager(config) {
    return {
        config,
        deleteModalOpen: false,
        deleting: false,
        deleteTarget: { id: null, name: '' },

        confirmDelete(id, name) {
            this.deleteTarget = { id, name };
            this.deleteModalOpen = true;
        },

        async deletePost() {
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

export function blogPostForm(config) {
    return {
        content: config.content?.length ? config.content : [],
        faqs: config.faqs?.length ? config.faqs : [],
        slugTouched: Boolean(config.slugTouched),
        featuredImagePreview: config.featuredImageUrl ?? null,
        status: config.status || 'draft',

        onTitleInput(event) {
            if (this.slugTouched) return;

            const slugInput = this.$refs.slugInput;
            if (slugInput) slugInput.value = slugify(event.target.value);
        },

        onSlugInput() {
            this.slugTouched = true;
        },

        onFeaturedImageChange(event) {
            const file = event.target.files[0];
            this.featuredImagePreview = file ? URL.createObjectURL(file) : this.featuredImagePreview;
        },

        addBlock(type) {
            this.content.push(emptyBlock(type));
        },

        removeBlock(index) {
            this.content.splice(index, 1);
        },

        moveBlock(index, direction) {
            const target = index + direction;
            if (target < 0 || target >= this.content.length) return;

            const [block] = this.content.splice(index, 1);
            this.content.splice(target, 0, block);
        },

        addListItem(blockIndex) {
            this.content[blockIndex].items.push('');
        },

        removeListItem(blockIndex, itemIndex) {
            this.content[blockIndex].items.splice(itemIndex, 1);
        },

        addTableColumn(blockIndex) {
            const block = this.content[blockIndex];
            block.headers.push(`Column ${block.headers.length + 1}`);
            block.rows.forEach((row) => row.push(''));
        },

        removeTableColumn(blockIndex, columnIndex) {
            const block = this.content[blockIndex];
            block.headers.splice(columnIndex, 1);
            block.rows.forEach((row) => row.splice(columnIndex, 1));
        },

        addTableRow(blockIndex) {
            const block = this.content[blockIndex];
            block.rows.push(block.headers.map(() => ''));
        },

        removeTableRow(blockIndex, rowIndex) {
            this.content[blockIndex].rows.splice(rowIndex, 1);
        },

        addFaq() {
            this.faqs.push({ question: '', answer: '' });
        },

        removeFaq(index) {
            this.faqs.splice(index, 1);
        },
    };
}

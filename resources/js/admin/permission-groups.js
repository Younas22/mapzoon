/**
 * Shared helpers for Alpine components that manage a `form.permissions`
 * array of selected permission IDs against grouped "select all" checkboxes.
 */
export function isGroupFullyChecked(selected, ids) {
    return ids.length > 0 && ids.every((id) => selected.includes(String(id)));
}

export function toggleGroupPermissions(selected, ids, checked) {
    const idStrings = ids.map(String);

    return checked
        ? [...new Set([...selected, ...idStrings])]
        : selected.filter((id) => ! idStrings.includes(id));
}

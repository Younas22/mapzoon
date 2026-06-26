export default function settingsForm(config) {
    return {
        config,
        activeTab: 'branding',
        logoDarkPreview: config.logoDarkUrl,
        logoLightPreview: config.logoLightUrl,
        faviconPreview: config.faviconUrl,

        onImageChange(event, previewKey) {
            const file = event.target.files[0];
            if (file) this[previewKey] = URL.createObjectURL(file);
        },
    };
}

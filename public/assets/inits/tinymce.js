/**
 * Initialize the tinyMCE editor.
 *
 * @param {string} selector
 * @param {object} options
 * @returns {object}
 * @see https://www.tiny.cloud/docs/
 */
return async (selector, options = {}) => {
    if (typeof tinyMCE === 'undefined') {
        console.error('TinyMCE is not loaded.');
        return;
    }

    // Get the theme from the data-bs-theme attribute
    const theme = $('[data-bs-theme]').attr('data-bs-theme');

    options = Object.assign({}, options, {
        selector: '#' + $(selector).attr('id'),
        language: document.documentElement.lang,
        directionality: document.documentElement.dir,
        height: 300,
        menubar: false,
        branding: false,
        skin: theme === 'dark' ? 'oxide-dark' : 'oxide',
        content_css: theme === 'dark' ? 'dark' : 'default',
        plugins: 'advlist autolink code directionality link lists table image',
        toolbar:
            'undo redo | styles | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify outdent indent ltr rtl | bullist numlist | table image',
        toolbar_mode: 'sliding',
        image_title: true,
        automatic_uploads: true,
        images_upload_url: '/tinymce/upload',
        file_picker_types: 'image',
        file_picker_callback: (cb, value, meta) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.onchange = () => {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = () => {
                    const id = 'blobid' + new Date().getTime();
                    const blobCache = tinyMCE.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    cb(blobInfo.blobUri(), { title: file.name });
                };

                reader.readAsDataURL(file);
            };

            input.click();
        },
    });

    const [instance] = await tinyMCE.init(options);
    $(selector).data('tinymce', instance);

    document.addEventListener('theme:changed', () => instance?.destroy(), { once: true });
};

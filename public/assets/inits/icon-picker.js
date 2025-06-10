/**
 * Bind events to the icon picker modal.
 */
function bindIconPickerEvents($content) {
    $content.find('[iconpicker-search]').on(
        'keyup',
        _.debounce(function () {
            const term = this.value.trim();

            // Early exit if the search term is empty.
            if (term.length === 0) {
                populateIconPicker($content, []);
                return;
            }

            const endpoint = 'https://api.fontawesome.com';
            const query = `query {
                search(version: "6.4.2", query: "${this.value}", first: 100) {
                    id,
                    familyStylesByLicense {
                        free {
                            family,
                            style
                        }
                    }
                }
            }`;

            fetch(`${endpoint}/?query=${query}`)
                .then((response) => response.json())
                .then((response) => populateIconPicker($content, response.data.search));
        }, 100),
    );

    $content.on('click', '[iconpicker-icon]', function () {
        $content.find('[iconpicker-list] [iconpicker-icon]').removeClass('selected');
        $(this).addClass('selected');
    });
}

/**
 * Populate the icon picker modal with icons.
 */
function populateIconPicker($content, icons) {
    $content.find('[iconpicker-list] [iconpicker-icon]').remove();

    for (const icon of icons) {
        // Skip if the icon has no free styles.
        if (icon.familyStylesByLicense.free.length === 0) {
            continue;
        }

        const style = icon.familyStylesByLicense.free[0].style;
        const cls = `fa-${style} fa-${icon.id}`;

        $content.find('[iconpicker-list]').append(`<div iconpicker-icon="${cls}"><i class="${cls}"></i></div>`);
    }

    $content.find('.empty').toggle($content.find('[iconpicker-list] [iconpicker-icon]').length === 0);
}

/**
 * Save the selected icon to the input.
 */
function saveIconPickerSelection($input, $content) {
    const $selected = $content.find('[iconpicker-list] [iconpicker-icon].selected');

    if ($selected.length === 0) {
        return;
    }

    $input.val($selected.attr('iconpicker-icon')).trigger('change');
}

/**
 * Initialize the icon picker input.
 *
 * @param {string} selector
 * @see https://fontawesome.com
 */
return (selector) => {
    const $input = $(selector);
    const $wrapper = $input.closest('[iconpicker-wrapper]');
    const $preview = $wrapper.find('[iconpicker-preview]');
    const $picker = $wrapper.find('[iconpicker-picker]');

    $input.on('change input', function () {
        $preview.attr('class', `icon icon-sm ${this.value}`);
    });

    $picker.on('click', function () {
        $.confirm({
            icon: 'far fa-font-awesome me-2',
            title: __('Select an icon'),
            content: $('[for="icon-picker"]').html(),
            onContentReady: function () {
                bindIconPickerEvents(this.$content);
            },
            buttons: {
                cancel: {
                    text: __('Cancel'),
                    btnClass: 'btn-secondary',
                },
                confirm: {
                    text: __('Select'),
                    btnClass: 'btn-primary',
                    action: function () {
                        saveIconPickerSelection($input, this.$content);
                    },
                },
            },
        });
    });

    $input.trigger('change');
};

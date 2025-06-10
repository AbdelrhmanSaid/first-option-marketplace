/* ---------------------------------
 * Observe initiators
 * --------------------------------- */

$(document).ready(() => {
    // Initialize the visibility plugin.
    RedotVisibility.init();

    // Watch for DOM changes and call the init function.
    const observer = new MutationObserver(() => {
        window.init();
    });

    // Listen for DOM changes and call the init function.
    observer.observe(document.body, {
        childList: true,
        subtree: true,
    });

    // Call the init function on page load.
    window.init();
});

/* ---------------------------------
 * JQuery Confirm
 * --------------------------------- */

jconfirm.defaults = {
    ...jconfirm.defaults,

    icon: 'fa fa-info-circle',
    type: 'dark',
    theme: 'material',
    animateFromElement: false,

    buttons: {
        // ...
    },

    // ...
};

/* ---------------------------------
 * Fancybox
 * --------------------------------- */

$.extend(true, $.fancybox.defaults, {
    beforeLoad: function (instance, slide) {
        let type = instance.current.contentType;

        // Append type to fancybox container
        slide.$slide.closest('.fancybox-container').addClass(`fancybox-type-${type}`);
    },
});

/* ---------------------------------
 * Redot Visibility
 * --------------------------------- */

// Disable validation on hidden elements
$(document).on('visibility:updated', '[visible-when]', (event, visible) => {
    const $container = $(event.target);
    const $targets = $container.is('[validation]') ? $container : $container.find('[validation]');

    if (visible) {
        $targets.removeAttr('disable-validation');
    } else {
        $targets.attr('disable-validation', true);
    }
});

/* ---------------------------------
 * Redot Validator
 * --------------------------------- */

// Disable HTML5 validation
$('form').attr('novalidate', true);

$(document).on('submit', 'form:not([disable-validation])', (event) => {
    event.preventDefault();

    const $form = $(event.target);
    const isValid = validateForm($form, true);

    // No errors, submit the form
    if (isValid) {
        const $submit = $form.find('[type="submit"]');
        const $spinner = $('<span class="spinner-border spinner-border-sm me-2" role="status"></span>');

        $submit.prepend($spinner);
        $submit.prop('disabled', true);

        return event.target.submit();
    }
});

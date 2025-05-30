/**
 * Initialize the litepicker date picker.
 *
 * @param {string} selector
 * @param {object} options
 * @returns {object}
 * @see https://litepicker.com/
 */
return (selector, options = {}) => {
    const defaultOptions = {
        element: $(selector).get(0),
        showTooltip: true,
        autoApply: true,
        allowRepick: true,
        lang: document.documentElement.lang,
        buttonText: {
            previousMonth: '<i class="fa fa-angle-left"></i>',
            nextMonth: '<i class="fa fa-angle-right"></i>',
        },
    };

    const selectorOptions = getOptionsFromSelector(selector, 'date-');
    options = _.merge(defaultOptions, selectorOptions, options);

    const picker = new Litepicker(options);
    $(selector).data('litepicker', picker);
};

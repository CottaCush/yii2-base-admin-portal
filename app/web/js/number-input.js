(function ($) {
    var numberInputDataSelector = '[data-number-input]',
        integerInputDataSelector = '[data-integer-input]',
        numberInputSelector = 'input[type="number"]',
        backspaceKeyCode = 8,
        deleteKeyCode = 46,
        zeroKeyCode = 48,
        nineKeyCode = 57;

    validateInput(numberInputSelector);
    validateInput(numberInputDataSelector);

    function validateInput(selector) {
        $(selector).keypress(function(e) {
            if (e.which !== backspaceKeyCode && e.which !== deleteKeyCode &&
                (e.which < zeroKeyCode || e.which > nineKeyCode)) {
                return false;
            }
        });
    }

    $(integerInputDataSelector).keypress(function(e) {
        if (e.which === deleteKeyCode) {
            return false;
        }
    });
})(jQuery);

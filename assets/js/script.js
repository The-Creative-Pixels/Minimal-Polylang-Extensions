/**
 * script.js
 * Front-end: toggle the language switcher dropdown.
 */
(function ($) {
    $(function () {
        $('#tcp-language-switcher .current-language').on('click', function () {
            $(this).siblings('.language-list').toggleClass('hidden');
        });
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#tcp-language-switcher').length) {
                $('#tcp-language-switcher .language-list').addClass('hidden');
            }
        });
    });
})(jQuery);
jQuery(document).ready(function ($) {
    'use strict';

    $('body').imagesLoaded({}, function () {

        let link = $('#sample-permalink').find('a').attr('href');

        $('#acf-group_repeater_builder_settings').prepend('<div class="preview-button-wrapper"><a href="' + link + '" class="preview-button">Preview Page</a></div>');

    });

});

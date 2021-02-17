jQuery(document).ready(function ($) {
    'use strict';

    $('body').imagesLoaded({}, function () {

        let link = $('#sample-permalink').find('a').attr('href');

        $('#acf-group_repeater_builder_settings').prepend('<div class="preview-button-wrapper"><a href="' + link + '" class="preview-button" target="_blank">Preview Page</a><a href="https://launchpad.swellstartups.com/support" target="_blank" class="help-button">Need Help?</a></div>');

        $('#acf-group_repeater_builder_settings').append('<div class="save-button-wrapper"><a class="save-button" href="#">Save Changes</a></div>');

        $('.save-button').click(function(e) {

            e.preventDefault();

            $('#publish').click();

        });

    });

});

jQuery(document).ready(function ($) {
    'use strict';

    $('body').imagesLoaded({}, function () {

        $('.faqs-accordion .toggle').click(function(e) {

            e.preventDefault();

            let $this = $(this);

            if ($this.next().hasClass('show')) {

                $this.next().find('.active').removeClass('active');

                $this.removeClass('active');

                $this.next().removeClass('show');

                $this.next().slideUp(350);

            } else {

                $this.addClass('active');

                $this.parent().parent().find('li .inner').removeClass('show');

                $this.parent().parent().find('li .inner').slideUp(350);

                $this.next().toggleClass('show');

                $this.next().slideToggle(350);

            }

        });

    });

});

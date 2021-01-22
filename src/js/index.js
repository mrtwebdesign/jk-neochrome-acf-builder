jQuery(document).ready(function ($) {
    'use strict';

    $('body').imagesLoaded({}, function () {

        $('.faqs-accordion .toggle').click(function (e) {

            e.preventDefault();

            let $this = $(this);

            if ($this.next().hasClass('show')) {

                console.log(1);

                $('.inner').find('.toggle').removeClass('active');

                $('.category-toggle').removeClass('active');

                $this.removeClass('active');

                $this.next().removeClass('show');

                $this.next().slideUp(350);

            } else {

                $('.inner').find('.toggle').removeClass('active');

                if ($this.hasClass('answer-toggle')) {

                    $('.category-toggle').not($this.parent().parent().parent().find('.category-toggle')).removeClass('active');

                } else {

                    $('.category-toggle').removeClass('active');

                }

                $this.addClass('active');

                $this.parent().parent().find('li .inner').removeClass('show');

                $this.parent().parent().find('li .inner').slideUp(350);

                $this.next().toggleClass('show');

                $this.next().slideToggle(350);

            }

        });

    });

});

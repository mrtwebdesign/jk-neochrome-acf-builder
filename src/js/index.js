jQuery(document).ready(function ($) {
    'use strict';

    const accordion_init = () => {

        let Accordion = function (el, multiple) {

            this.el = el || {};

            this.multiple = multiple || false;

            let link = this.el.find('.coach-open-link');

            link.on('click', {el: this.el, multiple: this.multiple}, this.dropdown);

        };

        Accordion.prototype.dropdown = function (e) {

            let $el = e.data.el,
                $this = $(this),
                $next = $this.next();

            $next.slideToggle();

            $this.parent().toggleClass('open');

            if (!e.data.multiple) {

                $el.find('.coach-open-text').not($next).slideUp().parent().removeClass('open');

            }

        };

        let accordion = new Accordion($('.coach-accordion'), false);

    };

    const navigation_init = () => {

        let isTopOfferClosed = false;

        $('.current-menu-item').children('a').addClass('current-menu-item-link');

        if ($(window).width() < 1025) {

            $('.menu-item-has-children').each(function () {

                $(this).append('<span class="menu-item-has-children-arrow"></span>');

                $('.menu-item-has-children-arrow').on('click', function (event) {

                    event.stopImmediatePropagation();

                    if ($(this).hasClass('active')) {

                        $(this).parent().find('.menu-item-has-children-arrow').removeClass('active');

                        $(this).parent().find('.sub-menu').removeClass('sub-menu-active');

                    } else {

                        $(this).addClass('active');

                        $(this).parent().children('.sub-menu:first').addClass('sub-menu-active');

                    }

                });

            });

        }

        if ($('.coach-top-offer').length > 0) {

            setTimeout(function () {

                $('.coach-top-offer').addClass('coach-show-top-offer');

                $('.page-wrapper').addClass('with-top-offer');

                $('.coach-search-popup').addClass('coach-search-with-top-offer');

                $('.coach-navbar').addClass('coach-navbar-with-top-offer');

            }, 2000);

            $('.coach-close-top-offer').on('click', function () {

                $('.coach-top-offer').removeClass('coach-show-top-offer');

                $('.page-wrapper').removeClass('with-top-offer');

                $('.coach-search-popup').removeClass('coach-search-with-top-offer');

                $('.coach-navbar').removeClass('coach-navbar-with-top-offer');

                isTopOfferClosed = true;

            });

        } else {
        }

        $(document).on('scroll', function () {

            if (($(this).scrollTop()) < 200 && !isTopOfferClosed) {

                $('.coach-top-offer').addClass('coach-show-top-offer');

                $('.page-wrapper').addClass('with-top-offer');

                $('.coach-search-popup').addClass('coach-search-with-top-offer');

                $('.coach-navbar').addClass('coach-navbar-with-top-offer');

            }

            if (($(this).scrollTop()) > 200 && !isTopOfferClosed) {

                $('.coach-top-offer').removeClass('coach-show-top-offer');

                $('.page-wrapper').removeClass('with-top-offer');

                $('.coach-search-popup').removeClass('coach-search-with-top-offer');

                $('.coach-navbar').removeClass('coach-navbar-with-top-offer');

            }

            if ($('.coach-navbar').hasClass('home-page')) {

                if (($(this).scrollTop()) < 200) {

                    $('.coach-navbar-button').addClass('is-hidden');

                    $('.coach-navbar-social').removeClass('is-hidden');

                }

                if (($(this).scrollTop()) > 200) {

                    $('.coach-navbar-button').removeClass('is-hidden');

                    $('.coach-navbar-social').addClass('is-hidden');

                }

            }

        });

        $('.coach-open-menu').on('click', function () {

            $('.coach-burger').toggleClass('coach-on-menu');

            $('.coach-navbar nav').toggleClass('coach-active-menu');

            $('.sub-menu').removeClass('sub-menu-active');

            $('.menu-item-has-children-arrow').removeClass('active');

        });

        $('body').on('click', '.mobile-search-button', function (e) {

            e.preventDefault();

            $('.coach-search-popup').toggleClass('coach-on-search');

        });

        $('.coach-search-button , .coach-close-search').on('click', function () {

            $('.coach-search-popup').toggleClass('coach-on-search');

        });

    };

    const isotope_init = () => {

        let $grid = $('.coach-isotope-grid').isotope({

            layoutMode: 'fitRows'

        });

        $('.coach-blog-filter').on('click', 'span', function () {

            $('.coach-blog-filter').find('span').removeClass('active');

            let filterValue = $(this).attr('data-filter');

            $(this).addClass('active');

            $grid.isotope({filter: filterValue});

        });

        $('.load-more-button').on('click', function () {

        });

    };

    const swiper_init = () => {

        let product_swiper = new Swiper('.coach-preview-frame-mobile', {
            slidesPerView: 1,
            slidesPerColumn: 1,
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable: true
            }
        });

        let swiper_testimonials = new Swiper('.coach-testimonial-slider', {
            slidesPerView: 1,
            spaceBetween: 15,
            speed: 1000,
            parallax: true,
            navigation: {
                nextEl: '.coach-testimonial-next',
                prevEl: '.coach-testimonial-prev',
            },
        });

        let swiper_instagram = new Swiper('.coach-instagram-slider', {
            slidesPerView: 6,
            spaceBetween: 0,
            speed: 1000,
            loop: true,
            autoplay: false,
            allowSlidePrev: false,
            allowSlideNext: false,
            breakpoints: {
                992: {
                    slidesPerColumn: 2,
                    slidesPerView: 3
                }
            },
        });

        let swiper_twits = new Swiper('.coach-twits-slider', {
            slidesPerView: 4,
            spaceBetween: 30,
            speed: 1000,
            loop: true,
            centeredSlides: true,
            navigation: {
                nextEl: '.coach-twitter-next',
                prevEl: '.coach-twitter-prev',
            },
            breakpoints: {
                992: {
                    slidesPerView: '1',
                },
            },
        });

        let swiper_twits_business = new Swiper('.coach-twits-slider-business', {
            slidesPerView: 6,
            spaceBetween: 30,
            speed: 1000,
            loop: true,
            centeredSlides: true,
            navigation: {
                nextEl: '.coach-twitter-next',
                prevEl: '.coach-twitter-prev',
            },
            breakpoints: {
                992: {
                    slidesPerView: '1',
                    navigation: false
                },
            },
        });

        let swiper_logotypes = new Swiper('.coach-logos-slider', {
            slidesPerView: 5,
            spaceBetween: 30,
        });

    };

    const select_arrow_woo = () => {

        let woo_ordering = $('.woocommerce-ordering'),
            orderby = woo_ordering.find('.orderby'),
            select_arrow = woo_ordering.find('.select-arrow');

        $(orderby).focus(function () {

            select_arrow.addClass('on');

        });

        $(orderby).focusout(function () {

            select_arrow.removeClass('on');

            $(orderby).removeClass('active');

        });

        $(orderby).on('click', function () {

            $(this).toggleClass('active');

            if (!$(orderby).hasClass("active")) {

                select_arrow.removeClass('on');

            } else {

                select_arrow.addClass('on');

            }

        });

    };

    $('body').imagesLoaded({}, function () {

        accordion_init();

        navigation_init();

        isotope_init();

        swiper_init();

        select_arrow_woo();

        if (!$('body').hasClass('elementor-editor-active')) {

            AOS.init();

        }

    });

});

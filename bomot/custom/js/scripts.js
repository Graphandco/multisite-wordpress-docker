$(document).ready(function () {
    // $(window).on('scroll', function () {
    //     if ($(window).scrollTop() > 50) {
    //         $('.elementor-location-header').addClass('scrolled');
    //     } else {
    //         //remove the background property so it comes transparent again (defined in your css)
    //         $('.elementor-location-header').removeClass('scrolled');
    //     }
    // });

    $('.comments-list').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        autoplay: true,
        // adaptiveHeight: true,
        // centerMode: true,
        // mobileFirst: true,
        nextArrow: '.temoignages-next',
        prevArrow: '.temoignages-prev',
    });

    $('.rea-list').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        autoplay: true,
        autoplaySpeed: 6000,
        // autoplay: true,
        // adaptiveHeight: true,
        // centerMode: true,
        // mobileFirst: true,
        nextArrow: '#rea-next',
        prevArrow: '#rea-prev',
    });

    function add_height(selecteur) {
        var headerHeight = $('.elementor-location-header').height();
        if ($(window).width() > 1024) {
            $('#hero .elementor-container').css('min-height', 'calc(100vh - ' + headerHeight + 'px)');
        } else {
            $('#hero .elementor-container').css('min-height', '35vh');
        }
    }
    add_height($(''));
    $(window).resize(function () {
        add_height($(''));
    });

    // function scrollToPosition(position) {
    //     $('html,body').animate({ scrollTop: position }, 900);
    // }
    // if (!$('body').hasClass('home')) {
    //     if ($(window).width() > 600) scrollToPosition($('#main').offset().top);
    // }

    gsap.registerPlugin(ScrollTrigger);
    gsap.config({ nullTargetWarn: false });

    ScrollTrigger.batch('.transform-left, .transform-right, .transform-top, .transform-bottom', {
        once: true,
        interval: 0,
        onEnter: (elements) => {
            gsap.to(elements, {
                // clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
                opacity: 1,
                x: 0,
                y: 0,
                rotation: 0,
                stagger: 0.15,
                duration: 0.25,
                ease: Expo.easeOut,
            });
        },
        start: 'top bottom-=250',
    });

    ScrollTrigger.batch('.transform-opacity', {
        once: true,
        interval: 0,
        onEnter: (elements) => {
            gsap.to(elements, {
                // clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
                opacity: 1,
                stagger: 0.2,
                duration: 0.5,
                ease: Expo.easeOut,
            });
        },
        start: 'top bottom-=250',
    });

    ScrollTrigger.batch('.number', {
        once: true,
        interval: 0,
        onEnter: (elements) => {
            gsap.to(elements, {
                // clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
                opacity: 1,
                x: 0,
                rotation: 0,
                stagger: 0.8,
                duration: 1,
                ease: Expo.easeOut,
            });
        },
        start: 'top bottom-=250',
    });
});

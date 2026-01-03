$(document).ready(function () {
	// function add_height(selecteur) {
	//     var headerHeight = $('.elementor-location-header').height();
	//     $('#hero .elementor-container').css('min-height', 'calc(30px + 100vh - ' + headerHeight + 'px)');
	//     // if ($(window).width() > 1024) {
	//     //     $('#hero .elementor-container').css('min-height', 'calc(100vh - ' + headerHeight + 'px)');
	//     // } else {
	//     //     $('#hero .elementor-container').css('min-height', 'auto');
	//     // }
	// }
	// add_height($(''));
	// $(window).resize(function () {
	//     add_height($(''));
	// });

	var delay = 500;
	setTimeout(function () {
		$(".elementor-tab-title").removeClass("elementor-active");
		$(".elementor-tab-content").css("display", "none");
	}, delay);

	$(window).on("load", function () {
		if ($("body").hasClass("home")) {
			$("#hero-slider .swiper").ready(function () {
				var mySwiper = document.querySelector(
					"#hero-slider .swiper"
				).swiper;
				$("#hero-slider-prev ").mousedown(function () {
					mySwiper.slidePrev();
				});
				$("#hero-slider-next").mousedown(function () {
					mySwiper.slideNext();
				});
			});
			$("#last-products-slider .swiper").ready(function () {
				var mySwiper = document.querySelector(
					"#last-products-slider .swiper"
				).swiper;
				$("#last-products-prev ").click(function () {
					mySwiper.slidePrev();
				});
				$("#last-products-next").click(function () {
					mySwiper.slideNext();
				});
			});
		}
	});

	// $('.jet-woo-builder-archive-add-to-cart').each(function () {
	//     $('.product_type_simple', this).append('<i class="fa fa-cart-plus" aria-hidden="true"></i>');
	// });

	// function scrollToPosition(position) {
	//     $('html,body').animate({ scrollTop: position }, 900);
	// }
	// if (!$('body').hasClass('home')) {
	//     if ($(window).width() > 600) scrollToPosition($('#main').offset().top);
	// }

	// gsap.registerPlugin(ScrollTrigger);
	// gsap.config({ nullTargetWarn: false });

	// ScrollTrigger.batch('.transform-left, .transform-right, .transform-top, .transform-bottom, .transform-opacity', {
	//     once: true,
	//     interval: 0,
	//     onEnter: (elements) => {
	//         gsap.to(elements, {
	//             // clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
	//             opacity: 1,
	//             x: 0,
	//             y: 0,
	//             stagger: 0.25,
	//             duration: 1,
	//             ease: Expo.easeOut,
	//         });
	//     },
	//     start: 'top bottom-=250',
	// });
});

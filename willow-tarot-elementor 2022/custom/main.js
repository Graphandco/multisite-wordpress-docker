$(document).ready(function () {
    gsap.registerPlugin(ScrollTrigger);
    gsap.config({ nullTargetWarn: false });

    ScrollTrigger.batch('.appear-right', {
        once: true,
        interval: 0.1,
        onEnter: (elements) => {
            gsap.to(elements, {
                opacity: 1,
                x: 0,
                stagger: 0.5,
                duration: 2,
                ease: Expo.easeOut,
                delay: 0.2,
            });
        },
        start: 'top bottom-=100',
    });

    ScrollTrigger.batch('.appear-left', {
        once: true,
        interval: 0.1,
        onEnter: (elements) => {
            gsap.to(elements, {
                opacity: 1,
                x: 0,
                stagger: 0.5,
                duration: 2,
                ease: Expo.easeOut,
            });
        },
        start: 'top bottom-=100',
    });

    ScrollTrigger.batch('.appear-top', {
        once: true,
        interval: 0.1,
        onEnter: (elements) => {
            gsap.to(elements, {
                opacity: 1,
                y: 0,
                stagger: 0.5,
                duration: 1.5,
                ease: Expo.easeOut,
            });
        },
        start: 'top bottom-=100',
    });

    /*French Date Picker*/

    // if ($('body').hasClass('page-id-33')) {
    //     function waitForFlatpicker(callback) {
    //         if (typeof window.flatpickr !== 'function') {
    //             setTimeout(function () {
    //                 waitForFlatpicker(callback);
    //             }, 100);
    //         }
    //         callback();
    //     }
    //     waitForFlatpicker(function () {
    //         flatpickr.l10ns.pt = {
    //             weekdays: {
    //                 shorthand: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
    //                 longhand: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    //             },
    //             months: {
    //                 shorthand: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
    //                 longhand: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
    //             },
    //             rangeSeparator: ' até ',
    //         };
    //         //set translations
    //         flatpickr.localize(flatpickr.l10ns.pt);
    //         flatpickr('.flatpickr-input');
    //         //set format
    //         $('.flatpickr-input').each(function () {
    //             flatpickr($(this)[0]).set('dateFormat', 'dd/mm/yyyy');
    //         });
    //     });
    // }
});

document.addEventListener("DOMContentLoaded", () => {
	/***************************************
        SCROLL AUTO JUSQU'AU CONTENU
    ***************************************/

	const declencheur = document.querySelector("#scroll-rdv");
	const cible = document.querySelector("#rdv");
	const declencheur2 = document.querySelector("#scroll-infos");
	const cible2 = document.querySelector("#infos");

	if (declencheur && cible) {
		declencheur.addEventListener("click", () => {
			cible.scrollIntoView({ behavior: "smooth" });
		});
	}
	if (declencheur2 && cible2) {
		declencheur2.addEventListener("click", () => {
			cible2.scrollIntoView({ behavior: "smooth" });
		});
	}

	/***************************************
    INIT GSAP
    ***************************************/

	/*Aide => https://greensock.com/cheatsheet/*/

	gsap.registerPlugin(ScrollTrigger);
	gsap.config({ nullTargetWarn: false });

	/***************************************
    SMOOTH SCROLL (LENIS + GSAP)
    ***************************************/
	const lenis = new Lenis();
	lenis.on("scroll", ScrollTrigger.update);
	gsap.ticker.add((time) => {
		lenis.raf(time * 1000);
	});
	gsap.ticker.lagSmoothing(0);

	/***************************************
        ANIMATIONS GSAP
    ***************************************/

	gsap.set("body:not(.elementor-editor-active) .transform-top", {
		opacity: 0,
		y: -100,
	});
	gsap.set("body:not(.elementor-editor-active) .transform-left", {
		opacity: 0,
		x: -100,
	});
	gsap.set("body:not(.elementor-editor-active) .transform-bottom", {
		opacity: 0,
		y: 30,
	});
	gsap.set("body:not(.elementor-editor-active) .transform-right", {
		opacity: 0,
		x: 100,
	});
	gsap.set("body:not(.elementor-editor-active) .transform-opacity", {
		opacity: 0,
	});
	gsap.set("body:not(.elementor-editor-active) .transform-rotate-x", {
		rotation: 90,
		x: 200,
		opacity: 0,
	});
	gsap.set("body:not(.elementor-editor-active) .transform-path", {
		opacity: 0,
		clipPath: "polygon(0 0, 100% 0, 100% 0, 0 0);",
	});

	ScrollTrigger.batch(
		".transform-left, .transform-right, .transform-top, .transform-bottom, .transform-opacity, .transform-rotate-x, .transform-path",
		{
			once: true,
			interval: 0,
			onEnter: (elements) => {
				gsap.to(elements, {
					// clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
					opacity: 1,
					x: 0,
					y: 0,
					stagger: 0.25,
					duration: 1,
					ease: Expo.easeOut,
					//ease: Elastic.easeOut,
					//ease: Bounce.easeOut,
				});
			},
			start: "top 90%",
		}
	);

	/***************************************
        ANIMATIONS GSAP LETTRE PAR LETTRE
    ***************************************/

	$(".anim-title .elementor-heading-title").each(function (index) {
		var characters = $(this).text().split("");

		$this = $(this);
		$this.empty();
		$.each(characters, function (i, el) {
			$this.append("<span>" + el + "</span");
		});
	});

	gsap.set(".anim-title .elementor-heading-title span", {
		opacity: 0,
	});

	ScrollTrigger.batch(".anim-title .elementor-heading-title span", {
		once: true,
		interval: 0,
		onEnter: (elements) => {
			gsap.to(elements, {
				// clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
				opacity: 1,
				rotate: 0,
				x: 0,
				y: 0,
				stagger: 0.075,
				duration: 0.05,
				//ease: Expo.easeOut,
				//ease: Elastic.easeOut,
				//ease: Bounce.easeOut,
			});
		},
		start: "top bottom-=250",
	});
});

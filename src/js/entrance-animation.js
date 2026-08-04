import { isBuilderMode, onDomReady, onElementorReady } from "./utils/runtime.js";

/*
Available classes:
 - entrance-animation
 - ea-fade
 - ea-slide-from-bottom
 - ea-slide-from-top
 - ea-slide-from-left
 - ea-slide-from-right
 - ea-blure
 - ea-delay-100
 - ea-delay-200
 - ea-speed-300
 - ea-speed-500
 - ea-speed-700
*/

(function () {
	"use strict";

	var SELECTOR = ".entrance-animation";
	var VISIBLE_CLASS = "is-animated-in";
	var INITIALIZED_FLAG = "entranceAnimationBound";
	var REDUCED_MOTION = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)");

	function revealElement(element) {
		element.classList.add(VISIBLE_CLASS);
	}

	function getAnimatedElements(root) {
		var scope = root || document;
		var elements = [];

		if (scope.matches && scope.matches(SELECTOR)) {
			elements.push(scope);
		}

		if (scope.querySelectorAll) {
			Array.prototype.push.apply(elements, scope.querySelectorAll(SELECTOR));
		}

		return elements;
	}

	function prepareElement(element) {
		if (element.dataset[INITIALIZED_FLAG] === "1") {
			return;
		}

		element.dataset[INITIALIZED_FLAG] = "1";
	}

	function setup(root) {
		var elements = getAnimatedElements(root);

		if (!elements.length) {
			return;
		}

		if (isBuilderMode()) {
			elements.forEach(function (element) {
				element.classList.add(VISIBLE_CLASS);
			});
			return;
		}

		if (REDUCED_MOTION && REDUCED_MOTION.matches) {
			elements.forEach(function (element) {
				element.classList.add(VISIBLE_CLASS);
			});
			return;
		}

		var observer = new IntersectionObserver(function (entries, observerInstance) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}

				revealElement(entry.target);
				observerInstance.unobserve(entry.target);
			});
		}, {
			threshold: 0.16,
			rootMargin: "0px 0px -10% 0px"
		});

		elements.forEach(function (element) {
			prepareElement(element);
			observer.observe(element);
		});
	}

	function boot() {
		setup(document);

		onElementorReady(function (root) {
			setup(root);
		});

	}

	onDomReady(boot);
})();

import { onDomReady, onElementorReady } from "./utils/runtime.js";

(function () {
	"use strict";

 
	var CONTAINER_SELECTOR = ".hbt-slider-container";
	var SLIDER_SELECTOR = "#hbt-slider .swiper, .swiper";
	var PANELS_WRAPPER_SELECTOR = ".hbt-slider-panels";
	var PANEL_SELECTOR = ".hbt-slider-side-panel";
	var MAX_RETRIES = 25;

	function getPanelIndex(panel) {
		var value = panel.getAttribute("data-slide-index");
		var index = Number.parseInt(value, 10);
		return Number.isFinite(index) ? index : null;
	}

	function assignPanelIndexes(panels) {
		panels.forEach(function (panel, index) {
			panel.setAttribute("data-slide-index", String(index));
		});
	}

	function setActivePanel(slideIndex, panels) {
		if (!panels.length) {
			return;
		}

		var totalPanels = panels.length;
		var normalizedIndex = ((slideIndex % totalPanels) + totalPanels) % totalPanels;

		panels.forEach(function (panel, index) {
			var panelIndex = getPanelIndex(panel);
			var isActive = (panelIndex === null ? index : panelIndex) === normalizedIndex;
			panel.classList.toggle("is-active", isActive);
			panel.setAttribute("aria-hidden", isActive ? "false" : "true");
		});
	}

	function bindSlider(swiperElement, panels) {
		if (!swiperElement || swiperElement.dataset.hbtSliderBound === "1" || !swiperElement.swiper) {
			return false;
		}

		var swiper = swiperElement.swiper;
		var lastIndex = null;
		var updatePanels = function () {
			if (swiper.realIndex === lastIndex) {
				return;
			}

			lastIndex = swiper.realIndex;
			setActivePanel(swiper.realIndex, panels);
		};

		swiper.on("slideChangeTransitionEnd", updatePanels);
		swiperElement.dataset.hbtSliderBound = "1";
		updatePanels();

		return true;
	}

	function getContainers(scope) {
		var containers = [];

		if (scope && scope.matches && scope.matches(CONTAINER_SELECTOR)) {
			containers.push(scope);
		}

		if (scope && scope.querySelectorAll) {
			scope.querySelectorAll(CONTAINER_SELECTOR).forEach(function (container) {
				if (containers.indexOf(container) === -1) {
					containers.push(container);
				}
			});
		}

		return containers;
	}

	function initContainer(container, retryCount) {
		var swiperElement = container.querySelector(SLIDER_SELECTOR);
		var panelsWrapper = container.querySelector(PANELS_WRAPPER_SELECTOR);
		var panels = panelsWrapper ? panelsWrapper.querySelectorAll(PANEL_SELECTOR) : [];

		if (!panels.length) {
			return;
		}

		assignPanelIndexes(panels);

		// No slider on this page/section: do not poll forever.
		if (!swiperElement) {
			return;
		}

		if (bindSlider(swiperElement, panels)) {
			return;
		}

		if ((retryCount || 0) >= MAX_RETRIES) {
			return;
		}

		window.setTimeout(function () {
			initContainer(container, (retryCount || 0) + 1);
		}, 120);
	}

	function init(root) {
		var scope = root || document;
		var containers = getContainers(scope);
		console.log("containers: ", containers.length);
		containers.forEach(function (container) {
			initContainer(container, 0);
		});
	}

	function boot() {
		init(document);
		onElementorReady(function (root) {
			init(root);
		});
	}

	onDomReady(boot);
})();
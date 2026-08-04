import { onDomReady, onElementorReady } from "./utils/runtime.js";

(function () {
	"use strict";

	var CONTAINER_SELECTOR = ".accordion-with-side-image-container";
	var ACCORDION_SELECTOR = ".accordion-with-side-image";
	var IMAGE_GRID_SELECTOR = ".accordion-with-side-images-grid";
	var SUMMARY_SELECTOR = ".e-n-accordion-item > summary";
	var IMAGE_SELECTOR = "img";
	var MAX_RETRIES = 25;

	function getSummaryIndex(summary, fallbackIndex) {
		var value = summary ? summary.getAttribute("data-accordion-index") : "";
		var parsed = Number.parseInt(value, 10);
		if (Number.isFinite(parsed) && parsed > 0) {
			return parsed;
		}

		return fallbackIndex + 1;
	}

	function setActiveImage(accordionImages, activeIndex) {
		accordionImages.forEach(function (image, index) {
			var isActive = index + 1 === activeIndex;
			image.classList.toggle("is-active", isActive);
			image.setAttribute("aria-hidden", isActive ? "false" : "true");
		});
	}

	function bindContainer(container) {
		if (!container || container.dataset.accordionWithSideImageBound === "1") {
			return true;
		}

		var accordion = container.querySelector(ACCORDION_SELECTOR);
		var imageGrid = container.querySelector(IMAGE_GRID_SELECTOR);
		if (!accordion || !imageGrid) {
			return false;
		}

		var accordionImages = Array.from(imageGrid.querySelectorAll(IMAGE_SELECTOR));
		var summaries = Array.from(accordion.querySelectorAll(SUMMARY_SELECTOR));

		if (!accordionImages.length || !summaries.length) {
			return false;
		}

		accordionImages.forEach(function (image) {
			image.classList.remove("is-active");
			image.setAttribute("aria-hidden", "true");
		});

		function updateFromOpenItem() {
			var activeSummary = accordion.querySelector(".e-n-accordion-item[open] > summary");
			if (!activeSummary) {
				setActiveImage(accordionImages, 0);
				return;
			}

			var activePosition = summaries.indexOf(activeSummary);
			setActiveImage(accordionImages, getSummaryIndex(activeSummary, Math.max(activePosition, 0)));
		}

		accordion.querySelectorAll(".e-n-accordion-item").forEach(function (item) {
			item.addEventListener("toggle", updateFromOpenItem);
		});

		summaries.forEach(function (summary, index) {
			summary.addEventListener("click", function () {
				window.setTimeout(function () {
					updateFromOpenItem();
				}, 0);
			});

			summary.addEventListener("keydown", function (event) {
				if (event.key === "Enter" || event.key === " ") {
					window.setTimeout(function () {
						updateFromOpenItem();
					}, 0);
				}
			});
		});

		container.dataset.accordionWithSideImageBound = "1";
		updateFromOpenItem();

		return true;
	}

	function init(root, retryCount) {
		var scope = root || document;
		var containers = scope.querySelectorAll ? Array.from(scope.querySelectorAll(CONTAINER_SELECTOR)) : [];

		if (!containers.length) {
			return;
		}

		var allBound = true;
		containers.forEach(function (container) {
			allBound = bindContainer(container) && allBound;
		});

		if (allBound) {
			return;
		}

		if ((retryCount || 0) >= MAX_RETRIES) {
			return;
		}

		window.setTimeout(function () {
			init(scope, (retryCount || 0) + 1);
		}, 120);
	}

	function boot() {
		init(document, 0);
		onElementorReady(function (root) {
			init(root, 0);
		});
	}

	onDomReady(boot);
})();
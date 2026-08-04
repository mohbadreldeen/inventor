import { onDomReady, onElementorReady } from "./utils/runtime.js";

(function () {
	"use strict";

	function getBuilderDeviceMode() {
		var mode = "";

		if (window.elementorFrontend && typeof window.elementorFrontend.getCurrentDeviceMode === "function") {
			mode = String(window.elementorFrontend.getCurrentDeviceMode() || "").toLowerCase();
		}

		if (!mode) {
			try {
				if (window.top && window.top !== window && window.top.document && window.top.document.body) {
					if (window.top.document.body.classList.contains("elementor-device-mobile") || window.top.document.body.classList.contains("elementor-device-mobile_extra")) {
						mode = "mobile";
					} else if (window.top.document.body.classList.contains("elementor-device-tablet") || window.top.document.body.classList.contains("elementor-device-tablet_extra")) {
						mode = "tablet";
					}
				}
			} catch (error) {
				// Ignore cross-window access issues and fall back to local checks.
			}
		}

		if (!mode && document.body) {
			if (document.body.classList.contains("elementor-device-mobile") || document.body.classList.contains("elementor-device-mobile_extra")) {
				mode = "mobile";
			} else if (document.body.classList.contains("elementor-device-tablet") || document.body.classList.contains("elementor-device-tablet_extra")) {
				mode = "tablet";
			}
		}

		return mode;
	}

	function isBuilderVerticalPreview() {
		var hasDeviceModeClass = !!(document.body && document.body.classList.contains("e-is-device-mode"));

		if (!hasDeviceModeClass) {
			try {
				if (window.top && window.top !== window && window.top.document && window.top.document.body) {
					hasDeviceModeClass = window.top.document.body.classList.contains("e-is-device-mode");
				}
			} catch (error) {
				// Ignore cross-window access issues and continue with local checks.
			}
		}

		if (!hasDeviceModeClass) {
			return false;
		}

		var mode = getBuilderDeviceMode();
		return mode === "mobile" || mode === "mobile_extra" || mode === "tablet" || mode === "tablet_extra";
	}

	function isVerticalViewport() {
		return window.matchMedia("(max-width: 1086px)").matches || isBuilderVerticalPreview();
	}

	function refreshAllAccordions() {
		document.querySelectorAll(".custom-accordion").forEach(updateAccordionWidths);
	}

	function getExpandedPanel(item) {
		var panel = item.querySelector(":scope > .custom-accordion-expanded");
		if (panel) {
			return panel;
		}

		var mediaBlocks = item.querySelectorAll(":scope > .custom-accordion-media");
		if (mediaBlocks.length > 1) {
			return mediaBlocks[mediaBlocks.length - 1];
		}

		return null;
	}

	function getExpandedElements(item) {
		var explicitPanel = item.querySelector(":scope > .custom-accordion-expanded");
		if (explicitPanel) {
			return [explicitPanel];
		}

		return Array.prototype.slice.call(item.children).filter(function (child) {
			return !child.classList.contains("custom-accordion-trigger");
		});
	}

	function getCollapsedWidth(container) {
		var styles = window.getComputedStyle(container);
		var value = parseFloat(styles.getPropertyValue("--acc-collapsed-w"));
		return Number.isFinite(value) ? value : 84;
	}

	function getGapWidth(container) {
		var styles = window.getComputedStyle(container);
		var value = parseFloat(styles.columnGap || styles.gap || styles.getPropertyValue("gap"));
		return Number.isFinite(value) ? value : 0;
	}

	function updateAccordionWidths(container) {
		var items = Array.prototype.slice.call(container.querySelectorAll(".custom-accordion-item"));
		if (!items.length) {
			return;
		}

		if (isVerticalViewport()) {
			items.forEach(function (item) {
				item.style.flex = "";
				item.style.width = "";
				item.style.maxWidth = "";
				item.style.minWidth = "";
			});
			return;
		}

		var containerWidth = container.getBoundingClientRect().width;
		var collapsedWidth = getCollapsedWidth(container);
		var gapWidth = getGapWidth(container);
		var activeItem = container.querySelector(".custom-accordion-item.is-active") || items[0];
		var inactiveCount = Math.max(items.length - 1, 0);
		var activeWidth = Math.max(containerWidth - (inactiveCount * collapsedWidth) - ((items.length - 1) * gapWidth), collapsedWidth);

		items.forEach(function (item) {
			var isActive = item === activeItem;
			var itemWidth = isActive ? activeWidth : collapsedWidth;
			item.style.flex = "0 0 " + itemWidth + "px";
			item.style.width = itemWidth + "px";
			item.style.maxWidth = itemWidth + "px";
			item.style.minWidth = itemWidth + "px";
		});
	}

	function activateAccordionItem(container, nextItem) {
		var items = container.querySelectorAll(".custom-accordion-item");

		items.forEach(function (item) {
			var isActive = item === nextItem;
			var trigger = item.querySelector(".custom-accordion-trigger");
			var panel = getExpandedPanel(item);
			var expandedElements = getExpandedElements(item);

			item.classList.toggle("is-active", isActive);
			item.setAttribute("aria-expanded", isActive ? "true" : "false");
			item.setAttribute("tabindex", trigger ? "-1" : (isActive ? "0" : "-1"));

			if (trigger) {
				trigger.setAttribute("aria-expanded", isActive ? "true" : "false");
				trigger.setAttribute("aria-hidden", isActive ? "true" : "false");
				trigger.setAttribute("tabindex", isActive ? "-1" : "0");
			}

			expandedElements.forEach(function (element) {
				if (isActive) {
					element.removeAttribute("hidden");
				} else {
					element.setAttribute("hidden", "hidden");
				}
			});

			if (panel) {
				panel.setAttribute("aria-hidden", isActive ? "false" : "true");
			}
		});

		updateAccordionWidths(container);
	}

	function bindItem(container, item) {
		if (!item || item.dataset.accordionBound === "1") {
			return;
		}

		var trigger = item.querySelector(".custom-accordion-trigger");
		var panel = getExpandedPanel(item);
		var panelId = item.getAttribute("data-panel") || "";

		if (trigger) {
			trigger.setAttribute("role", "button");
			trigger.setAttribute("tabindex", item.classList.contains("is-active") ? "-1" : "0");
		} else {
			item.setAttribute("role", "button");
			item.setAttribute("tabindex", item.classList.contains("is-active") ? "0" : "-1");
		}

		if (panel) {
			if (panelId && !panel.id) {
				panel.id = panelId;
			}
			if (panel.id && trigger) {
				trigger.setAttribute("aria-controls", panel.id);
			}
			if (panel.id) {
				item.setAttribute("aria-controls", panel.id);
			}
			panel.setAttribute("role", "region");
		}

		(trigger || item).addEventListener("keydown", function (event) {
			if (event.key !== "Enter" && event.key !== " ") {
				return;
			}
			event.preventDefault();
			if (!item.classList.contains("is-active")) {
				activateAccordionItem(container, item);
			}
		});

		(trigger || item).addEventListener("click", function (event) {
			if (item.classList.contains("is-active")) {
				return;
			}

			if (event.target && event.target.closest("a, button, input, select, textarea")) {
				return;
			}

			event.preventDefault();
			event.stopPropagation();
			activateAccordionItem(container, item);
		}, true);

		item.dataset.accordionBound = "1";
	}

	function handleAccordionActivation(event, container) {
		var item = event.target && event.target.closest ? event.target.closest(".custom-accordion-item") : null;
		if (!item || !container.contains(item)) {
			return;
		}

		if (event.target && event.target.closest("a, button, input, select, textarea")) {
			return;
		}

		if (!item.classList.contains("is-active")) {
			event.preventDefault();
			event.stopPropagation();
			if (typeof event.stopImmediatePropagation === "function") {
				event.stopImmediatePropagation();
			}
			activateAccordionItem(container, item);
		}
	}

	function handleGlobalAccordionCapture(event) {
		if (!event.target || !event.target.closest) {
			return;
		}

		var container = event.target.closest(".custom-accordion");
		if (!container) {
			return;
		}

		if (event.target.closest("a, button, input, select, textarea") && !event.target.closest(".custom-accordion-trigger")) {
			return;
		}

		handleAccordionActivation(event, container);
	}

	function setupAccordion(container) {
		if (!container) {
			return;
		}

		var items = container.querySelectorAll(".custom-accordion-item");
		var defaultPanel = container.getAttribute("data-default-acc");
		var activeItem = container.querySelector(".custom-accordion-item.is-active");

		if (!items.length) {
			return;
		}

		items.forEach(function (item) {
			bindItem(container, item);

			if (!activeItem && defaultPanel && item.getAttribute("data-panel") === defaultPanel) {
				activeItem = item;
			}
		});

		if (!activeItem) {
			activeItem = items[0];
		}

		if (!container.dataset.accordionCaptureBound) {
			container.addEventListener("click", function (event) {
				handleAccordionActivation(event, container);
			}, true);

			container.addEventListener("pointerdown", function (event) {
				var item = event.target && event.target.closest ? event.target.closest(".custom-accordion-item") : null;
				if (!item || !container.contains(item)) {
					return;
				}

				if (!event.target.closest("a, button, input, select, textarea") && !item.classList.contains("is-active")) {
					item.dataset.pointerActive = "1";
				}
			}, true);

			container.addEventListener("pointerup", function (event) {
				var item = event.target && event.target.closest ? event.target.closest(".custom-accordion-item") : null;
				if (!item || !container.contains(item)) {
					return;
				}

				if (item.dataset.pointerActive === "1") {
					delete item.dataset.pointerActive;
					handleAccordionActivation(event, container);
				}
			}, true);

			container.dataset.accordionCaptureBound = "1";
		}

		activateAccordionItem(container, activeItem);
		updateAccordionWidths(container);
		container.dataset.accordionInitialized = "1";
	}

	function initAll(root) {
		var scope = root || document;

		if (scope.matches && scope.matches(".custom-accordion")) {
			setupAccordion(scope);
		}

		var accordions = scope.querySelectorAll(".custom-accordion");
		accordions.forEach(setupAccordion);
	}

	function boot() {
		initAll(document);

		window.addEventListener("resize", refreshAllAccordions);

		try {
			var targetBody = window.top && window.top !== window && window.top.document ? window.top.document.body : document.body;
			if (targetBody && typeof MutationObserver !== "undefined" && !document.documentElement.dataset.accordionDeviceObserverBound) {
				var observer = new MutationObserver(refreshAllAccordions);
				observer.observe(targetBody, { attributes: true, attributeFilter: ["class"] });
				document.documentElement.dataset.accordionDeviceObserverBound = "1";
			}
		} catch (error) {
			// Ignore cross-window access issues when Elementor builder runs in an iframe.
		}

		if (!document.documentElement.dataset.accordionGlobalBound) {
			document.addEventListener("pointerdown", handleGlobalAccordionCapture, true);
			document.addEventListener("click", handleGlobalAccordionCapture, true);
			document.documentElement.dataset.accordionGlobalBound = "1";
		}

		onElementorReady(function (root) {
			initAll(root);
		});

	}

	onDomReady(boot);
})();

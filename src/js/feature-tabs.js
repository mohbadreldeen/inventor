import { onDomReady, onElementorReady, isBuilderMode } from "./utils/runtime.js";

(function () {
	"use strict";

	function isBuilderMobileViewport() {
		var mode = "";
		var hasDeviceModeClass = false;

		if (document.body) {
			hasDeviceModeClass = document.body.classList.contains("e-is-device-mode");
		}

		if (!hasDeviceModeClass) {
			try {
				if (window.top && window.top !== window && window.top.document && window.top.document.body) {
					hasDeviceModeClass = window.top.document.body.classList.contains("e-is-device-mode");
				}
			} catch (error) {
				// Ignore cross-window access issues and continue with other checks.
			}
		}

		if (!isBuilderMode() && !hasDeviceModeClass) {
			return false;
		}

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

		return mode === "mobile" || mode === "mobile_extra" || mode === "tablet" || mode === "tablet_extra";
	}

	function isMobileViewport() {
		return window.matchMedia("(max-width: 1086px)").matches || isBuilderMobileViewport();
	}

	function syncMobileContext(container) {
		if (!container) {
			return;
		}

		var isMobile = isMobileViewport();
		container.classList.toggle("is-mobile-context", isMobile);

		if (!isMobile && container.classList.contains("is-mobile-nav-open")) {
			closeMobileNav(container);
		}
	}

	function bindResponsiveContext(container) {
		if (!container || container.dataset.featureTabsViewportBound === "1") {
			syncMobileContext(container);
			return;
		}

		var sync = function () {
			syncMobileContext(container);
		};

		window.addEventListener("resize", sync);
		if (window.visualViewport && typeof window.visualViewport.addEventListener === "function") {
			window.visualViewport.addEventListener("resize", sync);
		}

		if (isBuilderMode()) {
			try {
				var targetBody = window.top && window.top !== window && window.top.document ? window.top.document.body : document.body;
				if (targetBody && typeof MutationObserver !== "undefined") {
					var observer = new MutationObserver(sync);
					observer.observe(targetBody, { attributes: true, attributeFilter: ["class"] });
				}
			} catch (error) {
				// Ignore cross-window access issues when Elementor builder runs in an iframe.
			}
		}

		container.dataset.featureTabsViewportBound = "1";
		syncMobileContext(container);
	}

	function getMobileHeader(container) {
		return container.querySelector(".custom-tabs-mobile-header");
	}

	function getTabsMenu(container) {
		return container.querySelector(".e-tabs-menu-base");
	}

	function getTabButtons(container) {
		return container.querySelectorAll('.e-tabs-menu-base [role="tab"], .e-tabs-menu-base .e-tab-base');
	}

	function getActiveTabButton(container) {
		var buttons = Array.from(getTabButtons(container));
		return buttons.find(function (button) {
			return button.classList.contains("e--selected") || button.getAttribute("aria-selected") === "true";
		}) || buttons[0] || null;
	}

	function getTabButtonLabel(button) {
		if (!button) {
			return "";
		}

		var labelNode = button.querySelector("span, .e-paragraph-base") || button;
		return (labelNode.textContent || "").trim();
	}

	function setMobileNavState(container, isOpen) {
		var header = getMobileHeader(container);
		var nav = getTabsMenu(container);

		if (!header || !nav) {
			return;
		}

		container.classList.toggle("is-mobile-nav-open", isOpen);
		header.setAttribute("aria-expanded", isOpen ? "true" : "false");
		nav.setAttribute("aria-hidden", isOpen ? "false" : "true");
	}

	function closeMobileNav(container) {
		setMobileNavState(container, false);
	}

	function toggleMobileNav(container) {
		setMobileNavState(container, !container.classList.contains("is-mobile-nav-open"));
	}

	function syncMobileHeaderLabel(container, activeButton) {
		var header = getMobileHeader(container);
		if (!header) {
			return;
		}

		var label = getTabButtonLabel(activeButton);
		if (!label) {
			return;
		}

		var headerLabel = header.querySelector(".custom-tabs-mobile-header-label");
		if (!headerLabel) {
			headerLabel = document.createElement("p");
			headerLabel.className = "custom-tabs-mobile-header-label";
			header.insertBefore(headerLabel, header.firstChild);
		}

		headerLabel.textContent = label;
		header.setAttribute("aria-label", label);
	}

	function bindHeader(container) {
		var header = getMobileHeader(container);
		var nav = getTabsMenu(container);

		if (!header || !nav) {
			container.classList.remove("has-mobile-header");
			return;
		}

		container.classList.add("has-mobile-header");

		if (!nav.id) {
			nav.id = "feature-tabs-nav-" + (container.getAttribute("data-id") || Math.random().toString(36).slice(2));
		}

		header.setAttribute("role", "button");
		header.setAttribute("tabindex", "0");
		header.setAttribute("aria-controls", nav.id);
		setMobileNavState(container, container.classList.contains("is-mobile-nav-open"));

		if (header.dataset.mobileNavBound === "1") {
			return;
		}

		header.addEventListener("pointerdown", function (event) {
			if (!isMobileViewport() || !isBuilderMode()) {
				return;
			}

			event.preventDefault();
			event.stopPropagation();
			toggleMobileNav(container);
		});

		header.addEventListener("click", function (event) {
			if (!isMobileViewport()) {
				return;
			}

			if (isBuilderMode()) {
				event.preventDefault();
				event.stopPropagation();
				return;
			}

			toggleMobileNav(container);
		});

		header.addEventListener("keydown", function (event) {
			if (!isMobileViewport()) {
				return;
			}

			if (event.key !== "Enter" && event.key !== " ") {
				return;
			}

			event.preventDefault();
			setMobileNavState(container, !container.classList.contains("is-mobile-nav-open"));
		});

		document.addEventListener("click", function (event) {
			if (!isMobileViewport()) {
				return;
			}

			if (container.classList.contains("is-mobile-nav-open") && !container.contains(event.target)) {
				closeMobileNav(container);
			}
		});

		document.addEventListener("keydown", function (event) {
			if (!isMobileViewport()) {
				return;
			}

			if (event.key === "Escape" && container.classList.contains("is-mobile-nav-open")) {
				closeMobileNav(container);
				header.focus();
			}
		});

		header.dataset.mobileNavBound = "1";
	}

	function bindTabButtons(container) {
		var buttons = getTabButtons(container);
		buttons.forEach(function (button) {
			if (button.dataset.featureTabsBound === "1") {
				return;
			}

			button.addEventListener("click", function () {
				window.requestAnimationFrame(function () {
					var activeButton = getActiveTabButton(container);
					syncMobileHeaderLabel(container, activeButton);
					if (isMobileViewport()) {
						closeMobileNav(container);
					}
				});
			});

			button.dataset.featureTabsBound = "1";
		});
	}

	function setupFeatureTabs(container) {
		if (!container) {
			return;
		}

		bindResponsiveContext(container);
		bindHeader(container);
		bindTabButtons(container);
		syncMobileHeaderLabel(container, getActiveTabButton(container));
	}

	function initAll(root) {
		var scope = root || document;
		if (scope.matches && scope.matches(".feature-tabs")) {
			setupFeatureTabs(scope);
		}

		scope.querySelectorAll(".feature-tabs").forEach(setupFeatureTabs);
	}

	function boot() {
		initAll(document);
		onElementorReady(function (root) {
			initAll(root);
		});
	}

	onDomReady(boot);
})();

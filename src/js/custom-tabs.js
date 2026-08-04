import { onDomReady, onElementorReady } from "./utils/runtime.js";

(function () {
	"use strict";

	function escapeIdSelector(value) {
		if (window.CSS && typeof window.CSS.escape === "function") {
			return window.CSS.escape(value);
		}
		return String(value).replace(/([ #;?%&,.+*~':"!^$\[\]()=>|/@])/g, "\\$1");
	}

	function isMobileViewport() {
		return window.matchMedia("(max-width: 1086px)").matches;
	}

	function setMobileNavState(container, isOpen) {
		var header = container.querySelector(".custom-tabs-mobile-header");
		var nav = container.querySelector(".custom-tabs-nav");

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

	function getTabButtonLabel(button) {
		if (!button) {
			return "";
		}

		var labelNode = button.querySelector(".tab-button-text") || button;
		return (labelNode.textContent || "").trim();
	}

	function syncMobileHeaderLabel(container, activeButton) {
		if (!container) {
			return;
		}

		var header = container.querySelector(".custom-tabs-mobile-header");
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

	function setupMobileNav(container) {
		if (!container) {
			return;
		}

		var header = container.querySelector(".custom-tabs-mobile-header");
		var nav = container.querySelector(".custom-tabs-nav");

		if (!header || !nav) {
			container.classList.remove("has-mobile-header");
			return;
		}

		container.classList.add("has-mobile-header");

		if (!nav.id) {
			nav.id = "custom-tabs-nav-" + (container.getAttribute("data-id") || Math.random().toString(36).slice(2));
		}

		header.setAttribute("role", "button");
		header.setAttribute("tabindex", "0");
		header.setAttribute("aria-controls", nav.id);

		if (container.classList.contains("is-mobile-nav-open")) {
			setMobileNavState(container, true);
		} else {
			setMobileNavState(container, false);
		}

		if (header.dataset.mobileNavBound === "1") {
			return;
		}

		header.addEventListener("click", function () {
			if (!isMobileViewport()) {
				return;
			}

			var isOpen = container.classList.contains("is-mobile-nav-open");
			setMobileNavState(container, !isOpen);
		});

		header.addEventListener("keydown", function (event) {
			if (!isMobileViewport()) {
				return;
			}

			if (event.key !== "Enter" && event.key !== " ") {
				return;
			}

			event.preventDefault();
			var isOpen = container.classList.contains("is-mobile-nav-open");
			setMobileNavState(container, !isOpen);
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

	function activateTab(container, button, panel) {
		var buttons = container.querySelectorAll(".custom-tab-btn");
		var panels = container.querySelectorAll(".custom-tab-panel");

		buttons.forEach(function (btn) {
			var isActive = btn === button;
			btn.classList.toggle("is-active", isActive);
			btn.setAttribute("aria-selected", isActive ? "true" : "false");
			btn.setAttribute("tabindex", isActive ? "0" : "-1");
		});

		panels.forEach(function (p) {
			var isActive = p === panel;
			p.classList.toggle("is-active", isActive);
			if (isActive) {
				p.removeAttribute("hidden");
				p.style.removeProperty("display");
			} else {
				p.setAttribute("hidden", "hidden");
				p.style.setProperty("display", "none", "important");
			}
		});

		syncMobileHeaderLabel(container, button);
	}

	function findPanel(container, panelId) {
		if (!panelId) {
			return null;
		}
		return container.querySelector("#" + escapeIdSelector(panelId));
	}

	function setupTabs(container) {
		if (!container) {
			return;
		}

		setupMobileNav(container);

		var buttons = container.querySelectorAll(".custom-tab-btn[data-tab-target]");
		var defaultId = container.getAttribute("data-default-tab");
		var activeButton = null;
		var activePanel = null;
		var uid = 0;

		if (!buttons.length) {
			return;
		}

		var hasAnyMappedPanel = false;
		var preActiveButton = container.querySelector(".custom-tab-btn.is-active[data-tab-target]");
		if (preActiveButton) {
			activeButton = preActiveButton;
			activePanel = findPanel(container, preActiveButton.getAttribute("data-tab-target"));
		}

		buttons.forEach(function (button) {
			var panelId = button.getAttribute("data-tab-target");
			var panel = findPanel(container, panelId);

			if (!button.id) {
				uid += 1;
				button.id = "custom-tab-btn-" + uid;
			}

			button.setAttribute("role", "tab");
			button.setAttribute("aria-controls", panelId);
			button.setAttribute("aria-selected", "false");
			button.setAttribute("tabindex", "-1");

			if (panel) {
				hasAnyMappedPanel = true;
				panel.setAttribute("role", "tabpanel");
				panel.setAttribute("aria-labelledby", button.id || "");
			}

			if (button.dataset.tabsBound !== "1") {
				button.addEventListener("click", function () {
					var targetId = button.getAttribute("data-tab-target");
					var targetPanel = findPanel(container, targetId);
					if (!targetPanel) {
						return;
					}
					activateTab(container, button, targetPanel);
					if (isMobileViewport()) {
						closeMobileNav(container);
					}
				});
				button.dataset.tabsBound = "1";
			}

			if (!activeButton && defaultId && panelId === defaultId && panel) {
				activeButton = button;
				activePanel = panel;
			}
		});

		if (!activeButton && hasAnyMappedPanel) {
			buttons.forEach(function (button) {
				if (activeButton) {
					return;
				}
				var panelId = button.getAttribute("data-tab-target");
				var panel = findPanel(container, panelId);
				if (panel) {
					activeButton = button;
					activePanel = panel;
				}
			});
		}

		if (!activePanel) {
			activePanel = container.querySelector(".custom-tab-panel");
			activeButton = buttons[0] || null;
		}

		if (activeButton && activePanel) {
			activateTab(container, activeButton, activePanel);
			container.dataset.tabsInitialized = "1";
		} else {
			container.dataset.tabsInitialized = "0";
		}
	}

	function initAll(root) {
		var scope = root || document;
		if (scope.matches && scope.matches(".custom-tabs")) {
			setupTabs(scope);
		}
		var tabSets = scope.querySelectorAll(".custom-tabs");
		tabSets.forEach(setupTabs);
	}

	function boot() {
		initAll(document);

		onElementorReady(function (root) {
			if (root.classList && root.classList.contains("custom-tabs")) {
				setupTabs(root);
			}

			initAll(root);
		});

	}

	onDomReady(boot);
})();

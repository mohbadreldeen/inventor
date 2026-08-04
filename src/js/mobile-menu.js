import { onDomReady } from "./utils/runtime.js";

(function () {
	"use strict";

	var TRIGGER_SELECTOR = "#mobile-menu-trigger";
	var DROPDOWN_SELECTOR = "#mobile-menu-dropdown";
	var OPEN_CLASS = "is-open";
	var SCROLL_LOCK_CLASS = "mobile-menu-open";
	var BOUND_FLAG = "mobileMenuGlobalBound";

	function setOpenState(trigger, dropdown, isOpen) {
		if (!dropdown) {
			return;
		}

		if (trigger) {
			trigger.setAttribute("aria-expanded", isOpen ? "true" : "false");
		}

		dropdown.classList.toggle(OPEN_CLASS, isOpen);
		dropdown.setAttribute("aria-hidden", isOpen ? "false" : "true");
		document.documentElement.classList.toggle(SCROLL_LOCK_CLASS, isOpen);
		if (document.body) {
			document.body.classList.toggle(SCROLL_LOCK_CLASS, isOpen);
		}
	}

	function ensureTriggerAccessibility(trigger) {
		if (!trigger) {
			return;
		}

		if (!trigger.hasAttribute("role")) {
			trigger.setAttribute("role", "button");
		}

		if (!trigger.hasAttribute("tabindex")) {
			trigger.setAttribute("tabindex", "0");
		}

		trigger.setAttribute("aria-controls", "mobile-menu-dropdown");
		if (!trigger.hasAttribute("aria-expanded")) {
			trigger.setAttribute("aria-expanded", "false");
		}
	}

	function bindMobileMenu() {
		if (document.documentElement.dataset[BOUND_FLAG] === "1") {
			return;
		}

		document.documentElement.dataset[BOUND_FLAG] = "1";

		document.addEventListener("click", function (event) {
			var trigger = event.target.closest ? event.target.closest(TRIGGER_SELECTOR) : null;
			var dropdown = document.querySelector(DROPDOWN_SELECTOR);

			if (trigger && dropdown) {
				event.preventDefault();
				ensureTriggerAccessibility(trigger);
				setOpenState(trigger, dropdown, !dropdown.classList.contains(OPEN_CLASS));
				return;
			}

			var activeTrigger = document.querySelector(TRIGGER_SELECTOR);
			if (!dropdown || !activeTrigger || !dropdown.classList.contains(OPEN_CLASS)) {
				return;
			}

			if (activeTrigger.contains(event.target) || dropdown.contains(event.target)) {
				return;
			}

			setOpenState(activeTrigger, dropdown, false);
		});

		document.addEventListener("keydown", function (event) {
			var trigger = document.querySelector(TRIGGER_SELECTOR);
			var dropdown = document.querySelector(DROPDOWN_SELECTOR);

			if (!trigger || !dropdown) {
				return;
			}

			ensureTriggerAccessibility(trigger);

			if ((event.key === "Enter" || event.key === " " || event.key === "Spacebar") && document.activeElement === trigger) {
				event.preventDefault();
				setOpenState(trigger, dropdown, !dropdown.classList.contains(OPEN_CLASS));
				return;
			}

			if (!dropdown.classList.contains(OPEN_CLASS)) {
				return;
			}

			if (event.key === "Escape" || event.key === "Esc") {
				setOpenState(trigger, dropdown, false);
			}
		});

		setOpenState(document.querySelector(TRIGGER_SELECTOR), document.querySelector(DROPDOWN_SELECTOR), false);
	}

	onDomReady(bindMobileMenu);
})();
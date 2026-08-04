import { onDomReady, onElementorReady } from "./utils/runtime.js";

(function () {
	"use strict";

	function getWidgetRoot(root) {
		if (!root) {
			return [];
		}

		var elements = [];

		if (root.matches && root.matches(".wp-before-after-image")) {
			elements.push(root);
		}

		if (root.querySelectorAll) {
			Array.prototype.push.apply(elements, root.querySelectorAll(".wp-before-after-image"));
		}

		return elements;
	}

	function updatePosition(widget, value) {
		var position = Math.max(0, Math.min(100, Number(value) || 0));
		widget.style.setProperty("--wp-before-after-position", position + "%");
	}

	function setupWidget(widget) {
		if (!widget || widget.dataset.beforeAfterBound === "1") {
			return;
		}

		var range = widget.querySelector(".wp-before-after-image__range");
		if (!range) {
			return;
		}

		widget.dataset.beforeAfterBound = "1";
		updatePosition(widget, range.value || range.getAttribute("value") || 50);

		range.addEventListener("input", function () {
			updatePosition(widget, range.value);
		});

		range.addEventListener("change", function () {
			updatePosition(widget, range.value);
		});
	}

	function init(root) {
		getWidgetRoot(root).forEach(setupWidget);
	}

	function boot() {
		init(document);

		onElementorReady(function (root) {
			init(root);
		});
	}

	onDomReady(boot);
})();